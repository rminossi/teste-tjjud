<?php

namespace Tests\Unit;

use App\Http\Requests\UpdateLivroRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;
use App\Models\Autor;
use App\Models\Assunto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateLivroRequestTest extends TestCase
{
    use RefreshDatabase;

    private function getValidData()
    {
        $autor = Autor::factory()->create();
        $assunto = Assunto::factory()->create();
        return [
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 29.90,
            'autores' => [$autor->CodAu],
            'assuntos' => [$assunto->CodAs],
        ];
    }

    public function test_valida_dados_corretos()
    {
        $data = $this->getValidData();
        $request = new UpdateLivroRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_falha_se_campo_obrigatorio_ausente()
    {
        $data = $this->getValidData();
        unset($data['Titulo']);
        $request = new UpdateLivroRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('Titulo', $validator->errors()->toArray());
    }

    public function test_falha_se_ano_publicacao_invalido()
    {
        $data = $this->getValidData();
        $data['AnoPublicacao'] = '20';
        $request = new UpdateLivroRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('AnoPublicacao', $validator->errors()->toArray());
    }

    public function test_falha_se_valor_negativo()
    {
        $data = $this->getValidData();
        $data['Valor'] = -10;
        $request = new UpdateLivroRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('Valor', $validator->errors()->toArray());
    }

    public function test_falha_se_autores_vazio()
    {
        $data = $this->getValidData();
        $data['autores'] = [];
        $request = new UpdateLivroRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('autores', $validator->errors()->toArray());
    }

    public function test_falha_se_assuntos_vazio()
    {
        $data = $this->getValidData();
        $data['assuntos'] = [];
        $request = new UpdateLivroRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('assuntos', $validator->errors()->toArray());
    }
} 