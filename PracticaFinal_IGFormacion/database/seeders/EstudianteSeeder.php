<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;

class EstudianteSeeder extends Seeder
{
    public function run()
    {
        Estudiante::factory(10)->create(); // Crea 10 estudiantes con la fábrica
    }
}

