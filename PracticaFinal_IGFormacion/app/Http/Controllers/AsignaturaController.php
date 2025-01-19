<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asignatura;

class AsignaturaController extends Controller
{
    public function index()
    {
        return Asignatura::all(); // Devuelve todas las asignaturas
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'curso_id' => 'required|exists:cursos,id', // Relación con Curso
            'profesor_id' => 'required|exists:profesores,id', // Relación con Profesor
        ]);

        return Asignatura::create($validated);
    }

    public function show($id)
    {
        return Asignatura::findOrFail($id); // Devuelve una asignatura específica
    }

    public function update(Request $request, $id)
    {
        $asignatura = Asignatura::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'string|max:255',
            'curso_id' => 'exists:cursos,id',
            'profesor_id' => 'exists:profesores,id',
        ]);

        $asignatura->update($validated);

        return $asignatura;
    }

    public function destroy($id)
    {
        $asignatura = Asignatura::findOrFail($id);
        $asignatura->delete();

        return response()->json(['message' => 'Asignatura eliminada'], 200);
    }
}
