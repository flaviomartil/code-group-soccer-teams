@extends('/layouts.layout')

@section('title', 'Jogadores Confirmados')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <h3 class="mt-5">Jogadores disponíveis</h3>
            @if (isset($jogadoresConfirmados) && count($jogadoresConfirmados) > 0)
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Nível</th>
                        <th>Goleiro</th>
                        <th>Confirmado</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($jogadoresConfirmados as $jogador)
                        <tr>
                            <td>{{ $jogador->nome }}</td>
                            <td>{{ $jogador->nivel }}</td>
                            <td>{{ $jogador->goleiro ? 'Sim' : 'Não' }}</td>
                            <td>{{ $jogador->confirmado ? 'Sim' : 'Não' }}</td>
                            <td>
                                <a href="{{ route('jogadores.confirmar', $jogador->id) }}"
                                   class="btn btn-success btn-sm" title="Confirmar Jogador">
                                    <i class="fas fa-check-circle"></i>
                                </a>

                                <a href="{{ route('jogadores.show', $jogador->id) }}" class="btn btn-info btn-sm"
                                   title="Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('jogadores.edit', $jogador->id) }}" class="btn btn-warning btn-sm"
                                   title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>

                                <form action="{{ route('jogadores.destroy', $jogador->id) }}" method="POST"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Excluir"
                                            onclick="return confirm('Tem certeza que deseja excluir?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p>Nenhum jogador sem jogo encontrado.</p>
            @endif

            <a href="{{ route('index') }}" class="btn btn-success">Voltar</a>

        </div>
    </div>
@endsection
