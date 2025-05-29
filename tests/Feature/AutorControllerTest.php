<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Autor;

class AutorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_autores()
    {
        Autor::factory()->count(3)->create();
        $response = $this->getJson('/api/autores');
        $response->assertStatus(200)->assertJsonStructure(['data']);
    }

    public function test_pode_criar_um_autor()
    {
        $dados = ['Nome' => 'Autor Teste'];
        $response = $this->postJson('/api/autores', $dados);
        $response->assertStatus(201)->assertJsonFragment($dados);
        $this->assertDatabaseHas('autores', $dados);
    }

    public function test_pode_mostrar_um_autor()
    {
        $autor = Autor::factory()->create();
        $response = $this->getJson("/api/autores/{$autor->CodAu}");
        $response->assertStatus(200)->assertJsonFragment(['Nome' => $autor->Nome]);
    }

    public function test_pode_atualizar_um_autor()
    {
        $autor = Autor::factory()->create();
        $novosDados = ['Nome' => 'Autor Atualizado'];
        $response = $this->putJson("/api/autores/{$autor->CodAu}", $novosDados);
        $response->assertStatus(200)->assertJsonFragment($novosDados);
        $this->assertDatabaseHas('autores', $novosDados);
    }

    public function test_pode_deletar_um_autor()
    {
        $autor = Autor::factory()->create();
        $response = $this->deleteJson("/api/autores/{$autor->CodAu}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('autores', ['CodAu' => $autor->CodAu]);
    }
}