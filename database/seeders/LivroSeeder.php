<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;

class LivroSeeder extends Seeder
{
    public function run(): void
    {
        $autores = Autor::all();
        $assuntos = Assunto::all();

        if ($autores->isEmpty() || $assuntos->isEmpty()) {
            $this->command->warn('Por favor, execute AutorSeeder e AssuntoSeeder primeiro.');
            return;
        }

        Livro::factory()->count(50)->create()->each(function ($livro) use ($autores, $assuntos) {
            $livro->autores()->attach(
                $autores->random(rand(1, 3))->pluck('CodAu')->toArray()
            );

            $livro->assuntos()->attach(
                $assuntos->random(rand(1, 2))->pluck('CodAs')->toArray()
            );
        });
    }
}