@extends('/layouts.layout')

@section('title', 'Jogadores do jogo')

@section('content')

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <!-- Exibição da mensagem de sucesso ou erro -->
                @if (isset($mensagem))
                    <div class="alert alert-success">{{ $mensagem }}</div>
                @endif

                <!-- Detalhes do Jogador -->
                <h3 class="mt-5">Detalhes do jogo</h3>
                @if (isset($soccerGame))
                    <table class="table">
                        <tr>
                            <th>Nome</th>
                            <td>{{ $soccerGame->nome }}</td>
                        </tr>
                        <tr>
                            <th>Data do jogo</th>
                            <td>{{ $soccerGame->data->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Total de jogadores no time</th>
                            <td>{{ $soccerGame->num_jogadores_por_time }}</td>
                        </tr>

                    </table>
                @else
                    <p>Jogo não encontrado.</p>
                @endif

                @if (isset($players))
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome Completo</th>
                            <th>Nível</th>
                            <th>Goleiro</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($players as $player)
                            <tr>
                                <td>{{ $player->id }}</td>
                                <td>{{ $player->nome }}</td>
                                <td>{{ $player->nivel }}</td>
                                <td>{{ $player->goleiro ? 'Sim' : 'Não' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
            </div>
            @endif
            <a href="{{ route('index') }}" class="btn btn-success">Voltar</a>

        </div>
    </div>

@endsection
