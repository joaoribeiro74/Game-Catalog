@extends('layouts.user')

@section('title')
    Detalhes do Jogo
@endsection

@section('content')
    <a href="{{ route('games.edit', ['id' => $game['id']]) }}">Editar</a>

    <p>
        <strong>ID:</strong> {{ $game['id'] }}
    </p>

    <p>
        <strong>Titulo:</strong> {{ $game['title'] }}
    </p>

    <p>
        <strong>Status:</strong> {{ $game['status'] }}
    </p>
@endsection