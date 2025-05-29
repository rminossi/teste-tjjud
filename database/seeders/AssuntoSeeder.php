<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assunto;

class AssuntoSeeder extends Seeder
{
    public function run(): void
    {
        Assunto::factory()->count(15)->create();
    }
}