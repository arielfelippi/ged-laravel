<?php

namespace App\Http\Controllers;

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
        $documento->usuario_id = auth()->user()->id;
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

        if (empty($documento_id)) {
            $documento = new Documentos();
        } else {
            $documento = Documentos::findOrFail($documento_id);
        }

        $documento->nome = "Rich-text";
        $documento->usuario_id = auth()->user()->id;
        $documento->conteudo = $request->input('editor');
        $documento->save();

        return redirect()->route('documentos.conteudo', ['id' => $documento->id])->with('success', 'Conteúdo salvo com sucesso!');
    }

    public function pesquisar(Request $request)
    {
        $termoBusca = trim($request->input('termo_busca', ''));

        if (empty($termoBusca)) {
            return Documentos::getDocumentos();
        }
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

        $documentoPermissao = new DocumentosPermissao();
        $documentoPermissao->documento_id = $documentoId;
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
}
