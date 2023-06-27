@extends('layouts.app')

@section('content')
    <header>
        <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
        <style>
            .container a.dash-permissoes {
                color: #ffc107 !important;
                text-decoration: none;
            }
            .container a.dash-permissoes:hover {
                color: #ffca2b !important;
                text-decoration: underline;
            }
            .container .search-form .btn-limpar {
                padding: 8px 16px;
                background-color: #00bee0b9;
                border: none;
                border-radius: 4px;
                color: #fff;
                font-size: 14px;
                cursor: pointer;
                text-decoration: none;
            }
            .container .search-form .btn-limpar:hover {
                background-color:#00bee0b9;
                text-decoration: none;
            }
            .container a.acoes-excluir {
                padding: 6px 10px;
                background-color: #f83c03b9;
                border: none;
                border-radius: 4px;
                color: #cac8c8;
                font-size: 14px;
                cursor: pointer;
                text-decoration: none;
            }
            .container a.acoes-excluir:hover {
                color: white !important;
                text-decoration: none;
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
        <h1>Dashboard</h1>
        <form action="{{ route('documentos.pesquisar') }}" method="POST">
            @csrf
            <div class="search-form">
                <input type="text" id="termo_busca" name="termo_busca" value="{{ request('termo_busca') }}" placeholder="Pesquisar documentos..." content="off">
                <button type="submit">Buscar</button>
                <a href="{{ route('dashboard') }}" class="btn-limpar">Limpar</a>
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
                        <th>Conteúdo</th>
                        <th>Data de Criação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documentos as $documento)
                        @php
                            $permissoes = str_replace(" |", "", trim($documento->permissoes));
                            $proprietario = ($documento->usuario_id == Auth::user()->id || $permissoes == 'todas');
                            $dataCriacao = $documento->criacao ?? $documento->created_at ?? $documento->updated_at;
                            $dataCriacao = date('d/m/Y H:i', strtotime($dataCriacao));
                            $conteudo = strip_tags(trim($documento->conteudo)) ?? 'Arquivo';
                        @endphp
                        <tr>
                            <td>{{ $documento->id }}</td>
                            <td>
                                @if($proprietario)
                                    @if ($documento->extensao == 'txt')
                                        <a class="dash-permissoes" href="{{ route('documentos.conteudo', ['id' => $documento->id]) }}">{{ $documento->nome }}</a>
                                    @else
                                        <a class="dash-permissoes" href="/storage/{{ $documento->caminho_arquivo }}" target="_blank">{{ $documento->nome }}</a>
                                    @endif
                                @elseif($permissoes == 'visualizar' && $documento->extensao != 'txt')
                                    <a class="dash-permissoes" href="/storage/{{ $documento->caminho_arquivo }}" target="_blank">{{ $documento->nome }}</a>
                                @elseif($permissoes == 'editar')
                                    <a class="dash-permissoes" href="{{ route('documentos.conteudo', ['id' => $documento->id]) }}">{{ $documento->nome }}</a>
                                @elseif($permissoes == 'excluir' && $documento->extensao != 'txt')
                                    <a class="dash-permissoes" href="{{ route('documentos.conteudo', ['id' => $documento->id]) }}">{{ $documento->nome }}</a>
                                @else
                                    {{ $documento->nome }}
                                @endif
                            </td>
                            <td>{{ $documento->nome_usuario }}</td>
                            <td>{{ $permissoes }}</td>
                            <td>{{ $conteudo }}</td>
                            <td>{{ $dataCriacao }}</td>
                            <td>
                                @if($proprietario|| ($permissoes == 'excluir'))
                                    <a class="acoes-excluir" href="{{ route('documentos.excluir', ['id' => $documento->id]) }}">Excluir</a>
                                @endif
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
