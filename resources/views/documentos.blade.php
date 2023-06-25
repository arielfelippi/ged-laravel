@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Documentos</h1>
        <form action="{{ route('documentos.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="documento">Upload de Documento</label>
                <input type="file" class="form-control-file" id="documento" name="documento" accept=".pdf,.doc,.docx">
            </div>
            <div class="form-group">
                <label for="conteudo">Redação de Documento</label>
                <textarea id="conteudo" name="conteudo"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </div>

    <script src="https://cdn.tiny.cloud/1/your-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea#conteudo',
            plugins: 'advlist autolink lists link image charmap print preview anchor',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image | link',
            menubar: false
        });
    </script>
@endsection
