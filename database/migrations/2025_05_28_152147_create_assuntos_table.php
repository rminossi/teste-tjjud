<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assuntos', function (Blueprint $table) {
            $table->id('CodAs');
            $table->string('Descricao', 40);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assuntos');
    }
};