<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evaluacion;

class EvaluacionController extends Controller
{
    public function index()
    {
        return Evaluacion::all(); // Devuelve todas las evaluaciones
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id', // Relación con Estudiante
            'asignatura_id' => 'required|exists:asignaturas,id', // Relación con Asignatura
            'curso_id' => 'required|exists:cursos,id', // Relación con Curso
            'nota' => 'required|numeric|between:0,10', // Nota entre 0 y 10
        ]);

        return Evaluacion::create($validated);
    }

    public function show($id)
    {
        return Evaluacion::findOrFail($id); // Devuelve una evaluación específica
    }

    public function update(Request $request, $id)
    {
        $evaluacion = Evaluacion::findOrFail($id);

        $validated = $request->validate([
            'estudiante_id' => 'exists:estudiantes,id',
            'asignatura_id' => 'exists:asignaturas,id',
            'curso_id' => 'exists:cursos,id',
            'nota' => 'numeric|between:0,10',
        ]);

        $evaluacion->update($validated);

        return $evaluacion;
    }

    public function destroy($id)
    {
        $evaluacion = Evaluacion::findOrFail($id);
        $evaluacion->delete();

        return response()->json(['message' => 'Evaluación eliminada'], 200);
    }
}
