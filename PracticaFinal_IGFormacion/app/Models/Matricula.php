<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'curso_id',
        'estudiante_id',
        'fecha_matricula',
    ];

    // Relación: Una matrícula pertenece a un estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }

    // Relación: Una matrícula pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }
}

