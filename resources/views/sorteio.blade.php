@extends('layouts.layout')

@section('title', 'Realizar Sorteio')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <h2 class="mb-4">Realizar Sorteio</h2>
            <form action="{{ route('sorteio.realizar') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label for="nomeDoJogo" class="form-label">Nome do jogo:</label>
                    <input type="text" class="form-control" name="nomeDoJogo" min="1" required>
                </div>

                <div class="mb-3">
                    <label for="dataDoJogo" class="form-label">Data do jogo:</label>
                    <input type="date" class="form-control" name="dataDoJogo"  required>
                </div>

                <div class="mb-3">
                    <label for="totalJogadoresTime" class="form-label">Total de Jogadores Por Time:</label>
                    <input type="number" class="form-control" name="totalJogadoresTime" min="1" required>
                </div>

                <button type="submit" class="btn btn-primary">Realizar sorteio</button>
            </form>
        </div>
    </div>
@endsection
