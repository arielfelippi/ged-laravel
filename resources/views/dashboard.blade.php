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
            <li><a href="/dashboard">Inicial</a></li>
            <li><a href="{{ route('documentos') }}">Documentos</a></li>
            <li><a href="{{ route('logout') }}" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a></li>
        </ul>
    </div>
    <div class="container">
        <h1>Dashboard</h1>
        <form action="{{ route('documentos.pesquisar') }}" method="POST">
            @csrf
            <div class="search-form">
                <input type="text" id="termo_busca" name="termo_busca" placeholder="Pesquisar documentos">
                <button type="submit">Buscar</button>
            </div>
        </form>
        @if(empty($documentos))
            <p>Nenhum documento encontrado.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome do Documento</th>
                        <th>Proprietário</th>
                        <th>Permissões</th>
                        <th>Data de Criação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documentos as $documento)
                        <tr>
                            <td>{{ $documento->id }}</td>
                            <td>{{ $documento->nome }}</td>
                            <td>{{ $documento->nome_usuario }}</td>
                            <td>{{ $documento->permissoes }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($documento->criacao)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
@endsection
