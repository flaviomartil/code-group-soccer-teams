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

                <!-- Formulário de Edição do Jogador -->
                <h3 class="mt-5">Editar Jogador</h3>
                @if (isset($player))
                    <form method="post" action="{{ route('jogadores.update', $player->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome" id="nome" class="form-control" value="{{ $player->nome }}"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="nivel">Nível:</label>
                            <input type="number" name="nivel" id="nivel" class="form-control"
                                   value="{{ $player->nivel }}" required>
                        </div>

                        <div class="form-group mb-3"> <!-- Adicionado margem inferior aqui -->
                            <label for="is_goleiro">Goleiro:</label>
                            <select name="is_goleiro" id="is_goleiro" class="form-control" required>
                                <option value="1" {{ $player->is_goleiro ? 'selected' : '' }}>Sim</option>
                                <option value="0" {{ !$player->is_goleiro ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>

                        <div class="form-group mb-3"> <!-- Adicionado margem inferior aqui -->
                            <label for="confirmado">Confirmado:</label>
                            <select name="confirmado" id="confirmado" class="form-control" required>
                                <option value="1" {{ $player->confirmado ? 'selected' : '' }}>Sim</option>
                                <option value="0" {{ !$player->confirmado ? 'selected' : '' }}>Não</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="{{ route('jogadores.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                @else
                    <p>Jogador não encontrado.</p>
                @endif

            </div>
        </div>
    </div>
@endsection
