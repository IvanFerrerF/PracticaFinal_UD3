<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MatriculaFactory extends Factory
{
    public function definition()
    {
        return [
            'curso_id' => \App\Models\Curso::factory(),
            'estudiante_id' => \App\Models\Estudiante::factory(),
            'fecha_matricula' => $this->faker->date('Y-m-d'),
        ];
    }
}

