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
                        <th>Documento</th>
                        <th>Proprietário</th>
                        <th>Permissões</th>
                        <th>Data de Criação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documentos as $documento)
                        @php
                            $permissoes = str_replace(" |", "", trim($documento->permissoes));
                        @endphp
                        <tr>
                            <td>{{ $documento->id }}</td>
                            <td>
                                @if($documento->usuario_id == Auth::user()->id || $permissoes == 'todas')
                                    <a href="{{ route('documentos.conteudo', ['id' => $documento->id]) }}">{{ $documento->nome }}</a>
                                    @else
                                    @if($permissoes == 'visualizar')
                                        <a href="/storage/{{ $documento->caminho_arquivo }}" target="_blank">{{ $documento->nome }}</a>
                                    @else
                                        {{ $documento->nome }}
                                    @endif
                                @endif
                            </td>
                            <td>{{ $documento->nome_usuario }}</td>
                            <td>{{ $permissoes }}</td>
                            <td>{{ date('d/m/Y H:i', strtotime($documento->criacao)) }}</td>
                            <td>
                                <form action="{{ route('documentos.compartilhar') }}" method="POST">
                                    @csrf
                                    <input type="password" id="documento_id" name="documento_id" hidden value="{{ $documento->id ?? 0 }}">
                                    <select name="usuario_id">
                                        <option value="">Selecionar usuário</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="permissao">
                                        <option value="visualizar">Visualizar</option>
                                        @if($documento->extensao == 'txt')
                                            <option value="editar">Editar</option>
                                        @endif
                                        <option value="excluir">Excluir</option>
                                    </select>
                                    <button type="submit">Compartilhar</button>
                                </form>
                            </td>
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
