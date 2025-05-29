@extends('layouts.app')

@section('title', 'Lista de Assuntos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Assuntos</h1>
        <a href="{{ route('assuntos.create') }}" class="btn btn-primary">Novo Assunto</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>CodAs</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($assuntos as $assunto)
                <tr>
                    <td>{{ $assunto->CodAs }}</td>
                    <td>{{ $assunto->Descricao }}</td>
                    <td>
                        <a href="{{ route('assuntos.show', $assunto->CodAs) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('assuntos.edit', $assunto->CodAs) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('assuntos.destroy', $assunto->CodAs) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este assunto?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhum assunto cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $assuntos->links('pagination::bootstrap-5') }}
@endsection