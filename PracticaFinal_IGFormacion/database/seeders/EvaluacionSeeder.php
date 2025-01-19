<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Evaluacion;

class EvaluacionSeeder extends Seeder
{
    public function run()
    {
        Evaluacion::factory(50)->create(); // Crea 50 evaluaciones con la fábrica
    }
}

