@extends('layouts.user')

@section('title')
    Atualização
@endsection

@section('content')
    <form action="" method="POST">
        @csrf

        <label for="title">Titulo</label>
        <input type="text" name="title" id="title" value="{{ $game['title'] }}">

        <button type="submit">Salvar</button>
    </form>
@endsection