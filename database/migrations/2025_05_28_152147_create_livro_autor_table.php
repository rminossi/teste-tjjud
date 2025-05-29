<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livro_autor', function (Blueprint $table) {
            $table->foreignId('Livro_CodL')->constrained('livros', 'CodL')->onDelete('cascade');
            $table->foreignId('Autor_CodAu')->constrained('autores', 'CodAu')->onDelete('cascade');
            $table->primary(['Livro_CodL', 'Autor_CodAu']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livro_autor');
    }
};