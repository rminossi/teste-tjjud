<?php

namespace Database\Factories;

use App\Models\Assunto;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssuntoFactory extends Factory
{
    protected $model = Assunto::class;

    public function definition(): array
    {
        return [
            'Descricao' => $this->faker->words(2, true),
        ];
    }
}