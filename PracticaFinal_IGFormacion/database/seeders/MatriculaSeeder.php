<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matricula;

class MatriculaSeeder extends Seeder
{
    public function run()
    {
        Matricula::factory(20)->create(); // Crea 20 matrículas con la fábrica
    }
}

