@extends('layouts.app')

@section('content')
    <header>
        <link rel="stylesheet" href="{{ asset('assets/css/documentos.css') }}">
        <script src="https://cdn.tiny.cloud/1/24a13y3aalsllnynwa6j6wdrv5r3v72ps2rtvn7zl75scwgf/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    </header>
    <div class="sidebar">
        <div class="user-info">
            <p>Bem vindo(a), {{ Auth::user()->name }}</p>
        </div>
        <ul>
            <li><a href="/dashboard">Inicial</a></li>
            <li><a href="{{ route('documentos') }}">Documentos</a></li>
            <li><a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Documentos</h1>
        <form action="{{ route('documentos.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="documento"></label>
                <input type="file" class="form-control-file" id="arquivo" name="arquivo" accept=".pdf,.doc,.docx">
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

        <form action="{{ route('documentos.conteudo.update') }}" method="POST">
            @csrf
            <input type="password" name="id" hidden value="{{ $documento->id ?? 0 }}">
            <div class="form-group">
                <label for="conteudo">Redação de Documento</label>
                <textarea id="conteudo" name="conteudo">{{ $documento->conteudo ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Salvar Conteúdo</button>
            </div>
        </form>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    <script>
        tinymce.init({
            selector: 'textarea#conteudo',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ],
            menubar: false
        });
    </script>
@endsection
