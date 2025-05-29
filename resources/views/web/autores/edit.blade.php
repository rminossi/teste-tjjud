@extends('layouts.app')

@section('title', 'Editar Autor')

@section('content')
    <h1>Editar Autor: {{ $autor->Nome }}</h1>
    <form action="{{ route('autores.update', $autor->CodAu) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="Nome" class="form-label">Nome do Autor <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('Nome') is-invalid @enderror" id="Nome" name="Nome" value="{{ old('Nome', $autor->Nome) }}" required maxlength="40">
            @error('Nome')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('autores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection