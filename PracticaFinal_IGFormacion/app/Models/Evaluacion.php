<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'estudiante_id',
        'asignatura_id',
        'curso_id',
        'nota',
    ];

    // Relación: Una evaluación pertenece a un estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Una evaluación pertenece a una asignatura
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    // Relación: Una evaluación pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}
