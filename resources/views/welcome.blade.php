@extends('layouts.app')

@section('title', 'Bem-vindo ao Sistema de Cadastro de Livros')

@section('content')
<div class="px-4 py-5 my-5 text-center">
    <h1 class="display-5 fw-bold">Sistema de Cadastro de Livros</h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">Bem-vindo ao sistema. Utilize o menu acima para navegar pelas seções de Livros, Autores e Assuntos, ou para gerar relatórios.</p> <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
            <a href="{{ route('livros.index') }}" class="btn btn-primary btn-lg px-4 gap-3">Ver Livros</a>
            <a href="{{ route('autores.index') }}" class="btn btn-outline-secondary btn-lg px-4">Ver Autores</a>
        </div>
    </div>
</div>
@endsection