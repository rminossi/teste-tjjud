<?php

namespace Tests\Unit;

use App\Http\Requests\StoreAssuntoRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreAssuntoRequestTest extends TestCase
{
    public function test_valida_descricao_correta()
    {
        $data = ['Descricao' => 'Assunto Teste'];
        $request = new StoreAssuntoRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_falha_descricao_vazia()
    {
        $data = ['Descricao' => ''];
        $request = new StoreAssuntoRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('Descricao', $validator->errors()->toArray());
    }

    public function test_falha_descricao_muito_longa()
    {
        $data = ['Descricao' => str_repeat('a', 21)];
        $request = new StoreAssuntoRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('Descricao', $validator->errors()->toArray());
    }
} 