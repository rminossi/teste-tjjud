<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
{
    DB::statement("DROP VIEW IF EXISTS View_Livros_Por_Autor");
    DB::statement("
        CREATE VIEW View_Livros_Por_Autor AS
        SELECT
            A.CodAu,
            A.Nome AS NomeAutor,
            L.CodL,
            L.Titulo AS TituloLivro,
            L.Editora,
            L.Edicao,
            L.AnoPublicacao,
            L.Valor
        FROM autores A
        JOIN livro_autor LA ON A.CodAu = LA.Autor_CodAu
        JOIN livros L ON LA.Livro_CodL = L.CodL
    ");
}

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS View_Livros_Por_Autor");
    }
};