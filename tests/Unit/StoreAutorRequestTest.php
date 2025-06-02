<?php

namespace Tests\Unit;

use App\Http\Requests\StoreAutorRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreAutorRequestTest extends TestCase
{
    public function test_valida_nome_correto()
    {
        $data = ['Nome' => 'Autor Teste'];
        $request = new StoreAutorRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->passes());
    }

    public function test_falha_nome_vazio()
    {
        $data = ['Nome' => ''];
        $request = new StoreAutorRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('Nome', $validator->errors()->toArray());
    }

    public function test_falha_nome_muito_longo()
    {
        $data = ['Nome' => str_repeat('a', 41)];
        $request = new StoreAutorRequest();
        $validator = Validator::make($data, $request->rules());
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('Nome', $validator->errors()->toArray());
    }
} 