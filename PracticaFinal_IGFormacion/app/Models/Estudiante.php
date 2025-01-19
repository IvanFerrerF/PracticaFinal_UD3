<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'fecha_nacimiento',
    ];

    // Relación: Un estudiante puede tener muchas matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'estudiante_id');
    }

    // Relación: Un estudiante puede tener muchas evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'estudiante_id');
    }
}

