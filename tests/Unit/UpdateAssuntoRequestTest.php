<?php

namespace Tests\Unit;

use App\Http\Requests\UpdateAssuntoRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateAssuntoRequestTest extends TestCase
{
    public function test_valida_descricao_correta()
    {
        $data = ['Descricao' => 'Assunto Teste'];
        $request = new UpdateAssuntoRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_falha_descricao_vazia()
    {
        $data = ['Descricao' => ''];
        $request = new UpdateAssuntoRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('Descricao', $validator->errors()->toArray());
    }

    public function test_falha_descricao_muito_longa()
    {
        $data = ['Descricao' => str_repeat('a', 21)];
        $request = new UpdateAssuntoRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('Descricao', $validator->errors()->toArray());
    }
} 