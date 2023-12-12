@extends('/layouts.layout')

@section('title', 'Página Inicial')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h2 class="mb-4">Bem-vindo ao Sorteio de Times</h2>
            <!-- Exibição da mensagem de sucesso ou erro -->
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
            <img
                src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSXGWI-NyFnE8EMn6fFcjuR4YosU8j9jHfLCGtKk20pUw&s"
                alt="Imagem de boas-vindas" class="img-fluid">
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h3>Jogos Confirmados</h3>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Jogo</th>
                    <th>Data do jogo</th>
                    <th>Total de Jogadores no Time</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody>
                @foreach($jogosConfirmados as $jogo)
                    <tr>
                        <td>{{ $jogo->id }}</td>
                        <td>{{ $jogo->nome }}</td>
                        <td>{{ $jogo->data->format('d/m/Y') }}</td>
                        <td>{{ $jogo->num_jogadores_por_time }}</td>
                        <td>
                            <a href="{{ route('jogos.show', $jogo->id) }}" class="btn btn-info btn-sm"
                               title="Detalhes do jogo">
                                <i class="fas fa-eye"></i>
                            </a>

                            <form action="{{ route('jogos.destroy', $jogo->id) }}" method="POST"
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Cancelar jogo"
                                        onclick="return confirm('Tem certeza que deseja cancelar o jogo?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
