<?php

namespace App\Http\Controllers;

use App\Models\Documentos;
use Illuminate\Http\Request;

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

        // Obtém a extensão do arquivo
        $extensao = $arquivo->extension() ?? 'txt';

        // Salva o arquivo em um diretório específico(storage/app/documentos).
        $caminho = $arquivo->store('documentos');

        // Cria um novo documento no banco de dados
        $documento = new Documentos();
        $documento->usuario_id = auth()->user()->id;
        $documento->nome = $arquivo->getClientOriginalName();
        $documento->extensao = $extensao;
        $documento->caminho_arquivo = $caminho;
        $documento->save();

        return redirect()->back()->with('success', 'Documento enviado com sucesso!');
    }

    public function showConteudo($id)
    {
        $documento = Documentos::findOrFail($id) ?? [];

        return view('documentos', ['documento' => $documento]);
    }

    public function updateConteudo(Request $request)
    {
        $id = $request->input('id', 0);

        if (empty($id)) {
            $documento = new Documentos();
        } else {
            $documento = Documentos::findOrFail($id);
        }

        $documento->nome = "";
        $documento->usuario_id = auth()->user()->id;
        $documento->conteudo = $request->input('conteudo');
        $documento->save();

        return redirect()->route('documentos.conteudo', ['id' => $documento->id])->with('success', 'Conteúdo salvo com sucesso!');
    }
}
