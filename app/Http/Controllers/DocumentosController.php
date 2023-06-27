<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Documentos;
use Illuminate\Http\Request;
use App\Models\DocumentosPermissao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentosController extends Controller
{
    public function index()
    {
        return view('documentos');
    }

    public function upload(Request $request)
    {
        // Validação do arquivo
        $request->validate([
            'arquivo' => 'required|mimes:pdf,doc,docx|max:6144',
        ]);

        if (!$request->hasFile('arquivo')) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao enviar o documento.');
        }

        // Obtém o arquivo enviado
        $arquivo = $request->file('arquivo');
        $nomeOriginal = $arquivo->getClientOriginalName();
        $nomeOriginal = str_replace(" ", "_", $nomeOriginal);
        $localArmazenamento = 'documentos';

        // Obtém a extensão do arquivo
        $extensao = $arquivo->extension() ?? 'txt';

        // Salva o arquivo em um diretório específico(storage/app/documentos).
        $caminho = $arquivo->storeAs($localArmazenamento, $nomeOriginal);

        // Cria um novo documento no banco de dados
        $documento = new Documentos();
        $documento->usuario_id = Auth::user()->id;
        $documento->nome = $nomeOriginal;
        $documento->extensao = $extensao;
        $documento->caminho_arquivo = $caminho;
        $documento->save();

        return redirect()->back()->with('success', 'Documento enviado com sucesso!');
    }

    public function showConteudo($id = 0)
    {
        $documentos = Documentos::findOrFail($id);

        return view('documentos', ['documento' => $documentos]);
    }

    public function updateConteudo(Request $request)
    {
        $documento_id = $request->input('documento_id', 0);

        $isNew = true;
        $documento = '';

        if (!empty($documento_id)) {
            $isNew = false;
            $documento = Documentos::findOrFail($documento_id);
        }

        if (empty($documento_id) || empty($documento)) {
            $documento = new Documentos();
        }

        if ($isNew) {
            $documento->usuario_id = Auth::user()->id;
        }

        $documento->nome = "Rich-text";
        $documento->conteudo = $request->input('content_editor');
        $documento->save();

        return redirect()->route('documentos.conteudo', ['id' => $documento->id])->with('success', 'Conteúdo salvo com sucesso!');
    }

    public function compartilhar(Request $request)
    {
        $documentoId = $request->input('documento_id');
        $usuarioId = $request->input('usuario_id');
        $permissao = $request->input('permissao');

        $permissoes = ['visualizar', 'editar', 'excluir'];

        if (empty($documentoId)) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao compartilhar o documento(documento_id inválido).');
        }

        if (empty($usuarioId)) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao compartilhar o documento(usuario_id inválido).');
        }

        if (!in_array($permissao, $permissoes)) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao compartilhar o documento(permissao inválida).');
        }

        $documentoPermissao = DocumentosPermissao::where('documento_id', $documentoId)->first();

        if (empty($documentoPermissao)) {
            $documentoPermissao = new DocumentosPermissao();
            $documentoPermissao->documento_id = $documentoId;
        }

        $documentoPermissao->usuario_id = $usuarioId;
        $documentoPermissao->pode_ver = ($permissao === 'visualizar');
        $documentoPermissao->pode_editar = ($permissao === 'editar');
        $documentoPermissao->pode_excluir = ($permissao === 'excluir');
        $documentoPermissao->save();

        return redirect()->back()->with('success', 'Documento compartilhado com sucesso!');
    }

    public function visualizar($caminhoArquivo)
    {
        $urlArquivo = Storage::url($caminhoArquivo);

        // Redirecionar para a URL do arquivo
        return redirect($urlArquivo);
    }

    public function pesquisar(Request $request)
    {
        $termoBusca = trim($request->input('termo_busca', ''));

        if (empty($termoBusca)) {
            return redirect()->route('dashboard');
        }

        $documentos = Documentos::query()
        ->when($termoBusca, function ($query, $termo)
        {
            $query->where(function ($subquery) use ($termo)
            {
                $subquery->where('nome', 'like', '%' . $termo . '%')
                    ->orWhere('extensao', 'like', '%' . $termo . '%')
                    ->orWhere('caminho_arquivo', 'like', '%' . $termo . '%')
                    ->orWhere('usuario_id', 'like', '%' . $termo . '%')
                    ->orWhere('created_at', 'like', '%' . $termo . '%')
                    ->orWhere('updated_at', 'like', '%' . $termo . '%')
                    ->orWhere('conteudo', 'like', '%' . $termo . '%');
            });
        })
        ->get();

        $usuarios = User::all();

        return view('dashboard', ['documentos' => $documentos, "usuarios" => $usuarios]);
    }

    public function excluir($id)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao excluir o documento(id inválido).');
        }

        // Encontre o documento pelo ID
        $documento = Documentos::find($id);

        if (empty($documento)) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao excluir o documento, documento não encontrado.');
        }

        $documento->delete();

        return redirect()->back()->with('success', 'Documento excluido com sucesso!');
    }
}
