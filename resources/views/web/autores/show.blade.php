@extends('layouts.app')

@section('title', 'Detalhes do Autor')

@section('content')
    <h1>Detalhes do Autor: {{ $autor->Nome }}</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Código:</strong> {{ $autor->CodAu }}</p>
            <p><strong>Nome:</strong> {{ $autor->Nome }}</p>
            <p><strong>Livros Cadastrados:</strong>
                @if($autor->livros->count() > 0)
                    <ul>
                        @foreach($autor->livros as $livro)
                            <li>{{ $livro->Titulo }}</li>
                        @endforeach
                    </ul>
                @else
                    Nenhum livro associado.
                @endif
            </p>
            <p><strong>Criado em:</strong> {{ $autor->created_at ? $autor->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
            <p><strong>Atualizado em:</strong> {{ $autor->updated_at ? $autor->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('autores.edit', $autor->CodAu) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('autores.index') }}" class="btn btn-secondary">Voltar para Lista</a>
    </div>
@endsection