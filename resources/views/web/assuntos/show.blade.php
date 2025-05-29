@extends('layouts.app')

@section('title', 'Detalhes do Assunto')

@section('content')
    <h1>Detalhes do Assunto: {{ $assunto->Descricao }}</h1>
    <div class="card">
        <div class="card-body">
            <p><strong>Código:</strong> {{ $assunto->CodAs }}</p>
            <p><strong>Descrição:</strong> {{ $assunto->Descricao }}</p>
            <p><strong>Livros com este Assunto:</strong>
                @if($assunto->livros->count() > 0)
                    <ul>
                        @foreach($assunto->livros as $livro)
                            <li>{{ $livro->Titulo }}</li>
                        @endforeach
                    </ul>
                @else
                    Nenhum livro associado a este assunto.
                @endif
            </p>
            <p><strong>Criado em:</strong> {{ $assunto->created_at ? $assunto->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
            <p><strong>Atualizado em:</strong> {{ $assunto->updated_at ? $assunto->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('assuntos.edit', $assunto->CodAs) }}" class="btn btn-warning">Editar</a>
        <a href="{{ route('assuntos.index') }}" class="btn btn-secondary">Voltar para Lista</a>
    </div>
@endsection