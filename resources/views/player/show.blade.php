@extends('/layouts.layout')

@section('title', 'Jogadores Confirmados')

@section('content')

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <!-- Exibição da mensagem de sucesso ou erro -->
                @if (isset($mensagem))
                    <div class="alert alert-success">{{ $mensagem }}</div>
                @endif

                <!-- Detalhes do Jogador -->
                <h3 class="mt-5">Detalhes do Jogador</h3>
                @if (isset($player))
                    <table class="table">
                        <tr>
                            <th>Nome</th>
                            <td>{{ $player->nome }}</td>
                        </tr>
                        <tr>
                            <th>Nível</th>
                            <td>{{ $player->nivel }}</td>
                        </tr>
                        <tr>
                            <th>Goleiro</th>
                            <td>{{ $player->goleiro ? 'Sim' : 'Não' }}</td>
                        </tr>

                        <tr>
                            <th>Confirmado</th>
                            <td>{{ $player->confirmado ? 'Sim' : 'Não' }}</td>
                        </tr>
                        <!-- Adicione mais detalhes conforme necessário -->
                    </table>
                    <a href="{{ route('jogadores.index') }}" class="btn btn-success">Voltar</a>
                @else
                    <p>Jogador não encontrado.</p>
                @endif

            </div>
        </div>
    </div>

@endsection
