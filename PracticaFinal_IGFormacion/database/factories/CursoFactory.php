<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CursoFactory extends Factory
{
    public function definition()
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'descripcion' => $this->faker->paragraph,
            'duracion' => $this->faker->numberBetween(1, 52),
            'fecha_inicio' => $this->faker->date('Y-m-d'),
            'fecha_fin' => $this->faker->date('Y-m-d', '+6 months'),
        ];
    }
}
