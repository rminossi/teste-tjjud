@extends('layouts.app')

@section('title', 'Novo Autor')

@section('content')
    <h1>Novo Autor</h1>
    <form action="{{ route('autores.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="Nome" class="form-label">Nome do Autor <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('Nome') is-invalid @enderror" id="Nome" name="Nome" value="{{ old('Nome') }}" required maxlength="40">
            @error('Nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('autores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection