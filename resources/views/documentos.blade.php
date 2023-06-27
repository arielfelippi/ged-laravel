@extends('layouts.app')

@section('content')
    <header>
        <link rel="stylesheet" href="{{ asset('assets/css/documentos.css') }}">
        <!-- <script src="assets/vendor/ckeditor5/build/ckeditor.js"></script> -->
        <script src="{{ asset('assets/js/ckeditor.min.js')}}"></script>
        <style>
            #container {
                width: 1000px;
                margin: 20px auto;
            }
            .ck.ck-editor__main>.ck-editor__editable {
                background-color: #333;
                color: #fff;
            }
            .ck-editor__editable[role="textbox"] {
                /* editing area */
                min-height: 200px;
            }
            .ck-content .image {
                /* block images */
                max-width: 80%;
                margin: 20px auto;
            }
        </style>
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
            @php
                $conteudo = '';
                $documento = $documento ?? '';

                if (!empty($documento) && isset($documento->conteudo)) {
                    $conteudo = $documento->conteudo;
                }
            @endphp
            <div class="form-group">
                <label for="documento"></label>
                <input type="file" class="form-control-file" id="arquivo" name="arquivo" accept=".pdf,.doc,.docx">
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

        <form action="{{ route('documentos.conteudo.update') }}" method="POST">
            @csrf
            <input type="password" id="documento_id" name="documento_id" hidden value="{{ $documento->id ?? 0 }}">

            <div class="form-group">
                <label for="conteudo" class="form-group">Redação de Documento</label>
                <br/>
                <textarea name="content_editor" id="editor">{{$conteudo}}</textarea>
            </div>
            <div class="form-group">
                <button id="btn_submit" type="submit" class="btn btn-primary">Salvar Conteúdo</button>
            </div>
        </form>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    <script>
        // let editor;
        // const data_content = <?php echo $conteudo; ?>

        ClassicEditor
            .create( document.querySelector( '#editor' ), {
            } )
            .catch( error => {
                console.error( error );
            } );
        // updateSourceElementOnDestroy: true,
        // .then( newEditor => {
        //     editor = newEditor;
        // } )
        // document.querySelector( '#btn_submit' ).addEventListener( 'click', () => {
        //     const editorData = editor.getData();
        //     editor.setData( data_content );
        // } );
    </script>

@endsection
