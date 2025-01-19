<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    use HasFactory;

    protected $table = 'profesores'; // Nombre de la tabla

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'especialidad',
    ];

    // Relación: Un profesor imparte muchas asignaturas
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'profesor_id');
    }
}

