<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;

class LivroControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_pode_listar_livros()
    {
        Livro::factory()->count(2)->create();
        $response = $this->getJson('/api/livros');
        $response->assertStatus(200)->assertJsonStructure(['data']);
    }

    public function test_pode_criar_um_livro()
    {
        $autor = Autor::factory()->create();
        $assunto = Assunto::factory()->create();

        $dados = [
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 29.90,
            'autores' => [$autor->CodAu],
            'assuntos' => [$assunto->CodAs],
        ];

        $response = $this->postJson('/api/livros', $dados);

        $response->assertStatus(201)
                 ->assertJsonFragment(['Titulo' => 'Livro Teste']);

        $this->assertDatabaseHas('livros', ['Titulo' => 'Livro Teste']);
    }

    public function test_pode_mostrar_um_livro()
    {
        $livro = Livro::factory()->create();
        $response = $this->getJson("/api/livros/{$livro->CodL}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['Titulo' => $livro->Titulo]);
    }

    public function test_pode_atualizar_um_livro()
    {
        $livro = Livro::factory()->create();
        $autor = Autor::factory()->create();
        $assunto = Assunto::factory()->create();

        $novosDados = [
            'Titulo' => 'Livro Atualizado',
            'Editora' => 'Editora Nova',
            'Edicao' => 2,
            'AnoPublicacao' => '2023',
            'Valor' => 49.90,
            'autores' => [$autor->CodAu],
            'assuntos' => [$assunto->CodAs],
        ];

        $response = $this->putJson("/api/livros/{$livro->CodL}", $novosDados);

        $response->assertStatus(200)
                 ->assertJsonFragment(['Titulo' => 'Livro Atualizado']);

        $this->assertDatabaseHas('livros', ['Titulo' => 'Livro Atualizado']);
    }

    public function test_pode_deletar_um_livro()
    {
        $livro = Livro::factory()->create();
        $response = $this->deleteJson("/api/livros/{$livro->CodL}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('livros', ['CodL' => $livro->CodL]);
    }
}