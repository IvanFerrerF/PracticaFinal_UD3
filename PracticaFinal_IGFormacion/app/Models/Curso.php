<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos'; // Nombre de la tabla

    // Relación: Un curso tiene muchas asignaturas
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'curso_id');
    }

    // Relación: Un curso tiene muchas evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'curso_id');
    }
}

