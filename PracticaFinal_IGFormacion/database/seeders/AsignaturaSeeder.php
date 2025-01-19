<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;

class AsignaturaSeeder extends Seeder
{
    public function run()
    {
        Asignatura::factory(15)->create(); // Crea 15 asignaturas con la fábrica
    }
}

