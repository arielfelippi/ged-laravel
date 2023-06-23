<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <div>
            <label for="name">Nome:</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirmar Senha:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
