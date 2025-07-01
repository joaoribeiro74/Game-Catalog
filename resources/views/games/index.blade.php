@extends('layouts.user')

@section('title')
    Início
@endsection

@section('content')
    <a href="{{ route('games.create') }}">Criar</a>

    <ul>
        @forelse($games as $game)
            <li>
                <a href="{{ route('games.detail', ['id' => $game['id']]) }}">
                    {{ $game['title'] }}
                </a>
            </li>
        @empty
            <li>
                Nenhum jogo cadastrado
            </li>
        @endforelse
    </ul>
@endsection