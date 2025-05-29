<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Assunto;

class AssuntoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_assuntos()
    {
        Assunto::factory()->count(3)->create();
        $response = $this->getJson('/api/assuntos');
        $response->assertStatus(200)->assertJsonStructure(['data']);
    }

    public function test_pode_criar_um_assunto()
    {
        $dados = ['Descricao' => 'Assunto Teste'];
        $response = $this->postJson('/api/assuntos', $dados);
        $response->assertStatus(201)->assertJsonFragment($dados);
        $this->assertDatabaseHas('assuntos', $dados);
    }

    public function test_pode_mostrar_um_assunto()
    {
        $assunto = Assunto::factory()->create();
        $response = $this->getJson("/api/assuntos/{$assunto->CodAs}");
        $response->assertStatus(200)->assertJsonFragment(['Descricao' => $assunto->Descricao]);
    }

    public function test_pode_atualizar_um_assunto()
    {
        $assunto = Assunto::factory()->create();
        $novosDados = ['Descricao' => 'Assunto Atualizado'];
        $response = $this->putJson("/api/assuntos/{$assunto->CodAs}", $novosDados);
        $response->assertStatus(200)->assertJsonFragment($novosDados);
        $this->assertDatabaseHas('assuntos', $novosDados);
    }

    public function test_pode_deletar_um_assunto()
    {
        $assunto = Assunto::factory()->create();
        $response = $this->deleteJson("/api/assuntos/{$assunto->CodAs}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('assuntos', ['CodAs' => $assunto->CodAs]);
    }
}