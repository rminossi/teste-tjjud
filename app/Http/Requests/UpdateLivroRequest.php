<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLivroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Titulo' => 'required|string|max:40',
            'Editora' => 'required|string|max:40',
            'Edicao' => 'required|integer|min:1',
            'AnoPublicacao' => 'required|string|size:4|regex:/^[0-9]{4}$/',
            'Valor' => 'required|numeric|min:0',
            'autores' => 'required|array|min:1',
            'autores.*' => 'required|integer|exists:autores,CodAu',
            'assuntos' => 'required|array|min:1',
            'assuntos.*' => 'required|integer|exists:assuntos,CodAs',
        ];
    }
}