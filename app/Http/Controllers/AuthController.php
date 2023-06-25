<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Documentos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return redirect('/login')->withInput([
            'email' => $user->email,
            'password' => $validatedData['password'],
        ]);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Credenciais inválidas',
        ]);
    }

    public function dashboard()
    {
        $userId = auth()->user()->id;

        $documentos = Documentos::where('usuario_id', $userId)
        ->orWhereIn('id', function ($query) use ($userId)
        {
            $query->select('documento_id')
                ->from('documentos_permissao')
                ->where('usuario_id', $userId);
        })
        ->get() ?? [];

        return view('dashboard', ['documentos' => $documentos]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout realizado com sucesso!');
    }
}
