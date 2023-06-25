@extends('layouts.app')

@section('content')
    <header>
        <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    </header>
    <div class="sidebar">
        <div class="user-info">
            <p>Bem vindo(a), {{ Auth::user()->name }}</p>
        </div>
        <ul>
            <li><a href="#">Inicial</a></li>
            <li><a href="#">Documentos</a></li>
            <li><a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Dashboard</h1>
        <div class="search-form">
            <input type="text" name="search" placeholder="Pesquisar documentos">
            <button type="submit">Buscar</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nome do Documento</th>
                    <th>Data de Upload</th>
                    <th>Proprietário</th>
                    <th>Compartilhado</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aqui você pode adicionar as linhas da tabela com os documentos -->
            </tbody>
        </table>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
@endsection
