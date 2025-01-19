<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AsignaturaFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->word,
            'curso_id' => \App\Models\Curso::factory(), // Relación con Curso
            'profesor_id' => \App\Models\Profesor::factory(), // Relación con Profesor
        ];
    }
}
