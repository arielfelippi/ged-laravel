<!DOCTYPE html>
<html>
<head>
    <title>Login de Usuário</title>
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
</head>
<body>
    <h1>Login de Usuário</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required><br>

        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
