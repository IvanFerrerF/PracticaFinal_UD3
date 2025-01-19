<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profesor;

class ProfesorSeeder extends Seeder
{
    public function run()
    {
        Profesor::factory(5)->create(); // Crea 5 profesores con la fábrica
    }
}

