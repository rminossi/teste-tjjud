@extends('layouts.app')

@section('title', 'Detalhes do Livro')

@section('content')
    <h1>Detalhes do Livro: {{ $livro->Titulo }}</h1>
    <div class="card">
        <div class="card-header">
            Informações do Livro
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Código (CodL):</strong> {{ $livro->CodL }}</p>
                    <p><strong>Título:</strong> {{ $livro->Titulo }}</p>
                    <p><strong>Editora:</strong> {{ $livro->Editora }}</p>
                    <p><strong>Edição:</strong> {{ $livro->Edicao }}ª</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Ano de Publicação:</strong> {{ $livro->AnoPublicacao }}</p>
                    <p><strong>Valor:</strong> R$ {{ number_format($livro->Valor, 2, ',', '.') }}</p>
                    <p><strong>Criado em:</strong> {{ $livro->created_at ? $livro->created_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                    <p><strong>Atualizado em:</strong> {{ $livro->updated_at ? $livro->updated_at->format('d/m/Y H:i:s') : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Autores
                </div>
                <div class="card-body">
                    @if($livro->autores->count() > 0)
                        <ul>
                            @foreach($livro->autores as $autor)
                                <li>{{ $autor->Nome }} (Cód: {{ $autor->CodAu }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p>Nenhum autor associado.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Assuntos
                </div>
                <div class="card-body">
                    @if($livro->assuntos->count() > 0)
                        <ul>
                            @foreach($livro->assuntos as $assunto)
                                <li>{{ $assunto->Descricao }} (Cód: {{ $assunto->CodAs }})</li>
                            @endforeach
                        </ul>
                    @else
                        <p>Nenhum assunto associado.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('livros.edit', $livro->CodL) }}" class="btn btn-warning">Editar Livro</a>
        <a href="{{ route('livros.index') }}" class="btn btn-secondary">Voltar para Lista de Livros</a>
    </div>
@endsection