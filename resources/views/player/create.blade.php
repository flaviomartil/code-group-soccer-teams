@extends('/layouts.layout')

@section('title', 'Cadastro de Jogadores')

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
            <h2 class="mb-4">Cadastro de Jogadores</h2>

            <!-- Formulário de Cadastro de Jogadores -->
            <form action="{{ route('jogadores.store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Jogador:</label>
                    <input type="text" class="form-control" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="nivel" class="form-label">Nível (1 a 5):</label>
                    <input type="number" class="form-control" name="nivel" min="1" max="5" required>
                </div>
                <div class="mb-3">
                    <label for="goleiro" class="form-label">É Goleiro?</label>
                    <select class="form-select" name="goleiro" required>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="confirmado" class="form-label">Confirmado?</label>
                    <select class="form-select" name="confirmado" required>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cadastrar Jogador</button>
            </form>

            <!-- Exibição da mensagem de sucesso ou erro -->
            @if (isset($mensagem))
                <div class="alert alert-success mt-3">{{ $mensagem }}</div>
            @endif
        </div>
    </div>
@endsection
