<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignaturas';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'curso_id',
        'profesor_id',
    ];

    // Relación: Una asignatura pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    // Relación: Una asignatura tiene muchas evaluaciones
    public function evaluaciones()
    {
        return $this->hasMany(Evaluacion::class, 'asignatura_id');
    }

    // Relación: Una asignatura tiene un profesor
    public function profesor()
    {
        return $this->belongsTo(Profesor::class, 'profesor_id');
    }
}

