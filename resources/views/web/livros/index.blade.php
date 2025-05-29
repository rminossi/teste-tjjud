@extends('layouts.app')

@section('title', 'Lista de Livros')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Livros</h1>
        <a href="{{ route('livros.create') }}" class="btn btn-primary">Novo Livro</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Cód.</th>
                <th>Título</th>
                <th>Editora</th>
                <th>Edição</th>
                <th>Ano</th>
                <th>Valor (R$)</th> <th>Autores</th>
                <th>Assuntos</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($livros as $livro)
                <tr>
                    <td>{{ $livro->CodL }}</td>
                    <td>{{ $livro->Titulo }}</td>
                    <td>{{ $livro->Editora }}</td>
                    <td>{{ $livro->Edicao }}</td>
                    <td>{{ $livro->AnoPublicacao }}</td>
                    <td>{{ number_format($livro->Valor, 2, ',', '.') }}</td> <td>
                        @foreach($livro->autores as $autor)
                            <span class="badge bg-secondary">{{ $autor->Nome }}</span>
                        @endforeach
                    </td>
                    <td>
                        @foreach($livro->assuntos as $assunto)
                            <span class="badge bg-info text-dark">{{ $assunto->Descricao }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('livros.show', $livro->CodL) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('livros.edit', $livro->CodL) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('livros.destroy', $livro->CodL) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este livro?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Nenhum livro cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $livros->links('pagination::bootstrap-5') }}
@endsection