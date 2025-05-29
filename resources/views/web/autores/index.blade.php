@extends('layouts.app')

@section('title', 'Lista de Autores')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Autores</h1>
        <a href="{{ route('autores.create') }}" class="btn btn-primary">Novo Autor</a>
    </div>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>CodAu</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($autores as $autor)
                <tr>
                    <td>{{ $autor->CodAu }}</td>
                    <td>{{ $autor->Nome }}</td>
                    <td>
                        <a href="{{ route('autores.show', $autor->CodAu) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('autores.edit', $autor->CodAu) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('autores.destroy', $autor->CodAu) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este autor?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Nenhum autor cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $autores->links('pagination::bootstrap-5') }}
@endsection