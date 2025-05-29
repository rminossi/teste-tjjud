<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('livro_assunto', function (Blueprint $table) {
            $table->foreignId('Livro_CodL')->constrained('livros', 'CodL')->onDelete('cascade');
            $table->foreignId('Assunto_CodAs')->constrained('assuntos', 'CodAs')->onDelete('cascade');
            $table->primary(['Livro_CodL', 'Assunto_CodAs']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livro_assunto');
    }
};