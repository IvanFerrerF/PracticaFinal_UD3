<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    // Mostrar todos los estudiantes
    public function index()
    {
        return Estudiante::all();
    }

    // Mostrar un estudiante específico
    public function show($id)
    {
        return Estudiante::find($id);
    }

    // Crear un nuevo estudiante
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email|unique:estudiantes,email',
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'required|date',
        ]);

        return Estudiante::create($data);
    }

    // Actualizar un estudiante
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre' => 'string',
            'apellidos' => 'string',
            'email' => 'email|unique:estudiantes,email,' . $id,
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'date',
        ]);

        $estudiante = Estudiante::find($id);
        $estudiante->update($data);

        return $estudiante;
    }

    // Eliminar un estudiante
    public function destroy($id)
    {
        Estudiante::destroy($id);
        return response()->json(['message' => 'Estudiante eliminado']);
    }
}
