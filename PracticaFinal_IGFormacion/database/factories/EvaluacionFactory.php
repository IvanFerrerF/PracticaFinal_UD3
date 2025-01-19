<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluacionFactory extends Factory
{
    public function definition()
    {
        return [
            'estudiante_id' => \App\Models\Estudiante::factory(),
            'asignatura_id' => \App\Models\Asignatura::factory(),
            'curso_id' => \App\Models\Curso::factory(),
            'nota' => $this->faker->randomFloat(2, 0, 10), // Nota entre 0 y 10 con 2 decimales
        ];
    }
}

