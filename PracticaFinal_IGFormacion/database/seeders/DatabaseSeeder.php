<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            EstudianteSeeder::class,
            ProfesorSeeder::class,
            CursoSeeder::class,
            AsignaturaSeeder::class,
            MatriculaSeeder::class,
            EvaluacionSeeder::class,
        ]);
    }
}
