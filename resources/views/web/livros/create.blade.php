@extends('layouts.app')

@section('title', 'Novo Livro')

@section('content')
    <h1>Novo Livro</h1>
    <form action="{{ route('livros.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="Titulo" class="form-label">Título <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('Titulo') is-invalid @enderror" id="Titulo" name="Titulo" value="{{ old('Titulo') }}" required maxlength="40">
                @error('Titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="Editora" class="form-label">Editora <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('Editora') is-invalid @enderror" id="Editora" name="Editora" value="{{ old('Editora') }}" required maxlength="40">
                @error('Editora') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="Edicao" class="form-label">Edição <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('Edicao') is-invalid @enderror" id="Edicao" name="Edicao" value="{{ old('Edicao') }}" required min="1">
                @error('Edicao') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="AnoPublicacao" class="form-label">Ano Publicação <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('AnoPublicacao') is-invalid @enderror" id="AnoPublicacao" name="AnoPublicacao" value="{{ old('AnoPublicacao') }}" required maxlength="4" placeholder="YYYY">
                @error('AnoPublicacao') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4 mb-3">
                <label for="Valor" class="form-label">Valor (R$) <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('Valor') is-invalid @enderror" id="Valor" name="Valor" value="{{ old('Valor', isset($livro) ? number_format($livro->Valor, 2, '.', '') : '') }}" required placeholder="Ex: 29,90">
                @error('Valor') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="mb-3">
            <label for="autores" class="form-label">Autores <span class="text-danger">*</span></label>
            <select multiple class="form-select @error('autores') is-invalid @enderror" id="autores" name="autores[]" required size="5">
                @foreach ($autores as $autor)
                    <option value="{{ $autor->CodAu }}" {{ (collect(old('autores'))->contains($autor->CodAu)) ? 'selected' : '' }}>
                        {{ $autor->Nome }}
                    </option>
                @endforeach
            </select>
            @error('autores') <div class="invalid-feedback">{{ $message }}</div> @enderror
             @error('autores.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="assuntos" class="form-label">Assuntos <span class="text-danger">*</span></label>
            <select multiple class="form-select @error('assuntos') is-invalid @enderror" id="assuntos" name="assuntos[]" required size="5">
                @foreach ($assuntos as $assunto)
                    <option value="{{ $assunto->CodAs }}" {{ (collect(old('assuntos'))->contains($assunto->CodAs)) ? 'selected' : '' }}>
                        {{ $assunto->Descricao }}
                    </option>
                @endforeach
            </select>
            @error('assuntos') <div class="invalid-feedback">{{ $message }}</div> @enderror
            @error('assuntos.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Salvar Livro</button>
        <a href="{{ route('livros.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var valorInput = document.getElementById('Valor');
        if (valorInput) {
            var currencyMask = IMask(valorInput, {
                mask: 'R$ num',
                blocks: {
                    num: {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: '.',
                        padFractionalZeros: true,
                        normalizeZeros: true,
                        radix: ',',
                        mapToRadix: ['.'],
                        min: 0,
                    }
                },
                prepare: function (str) {
                    return str;
                },
            });

            var form = valorInput.closest('form');
            if (form) {
                form.addEventListener('submit', function(event) {
                    if (currencyMask && currencyMask.unmaskedValue) {
                        valorInput.value = currencyMask.unmaskedValue;
                    }
                    const formData = new FormData(form);
                    for (let [key, value] of formData.entries()) {
                        console.log(key, value);
                    }
                });
            }
        }
    });
</script>
@endpush