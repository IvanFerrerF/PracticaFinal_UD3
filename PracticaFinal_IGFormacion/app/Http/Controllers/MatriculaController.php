<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;

class MatriculaController extends Controller
{
    public function index()
    {
        return Matricula::all(); // Devuelve todas las matrículas
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id', // Relación con Curso
            'estudiante_id' => 'required|exists:estudiantes,id', // Relación con Estudiante
            'fecha_matricula' => 'required|date', // Validar que sea una fecha válida
        ]);

        return Matricula::create($validated);
    }

    public function show($id)
    {
        return Matricula::findOrFail($id); // Devuelve una matrícula específica
    }

    public function update(Request $request, $id)
    {
        $matricula = Matricula::findOrFail($id);

        $validated = $request->validate([
            'curso_id' => 'exists:cursos,id',
            'estudiante_id' => 'exists:estudiantes,id',
            'fecha_matricula' => 'date',
        ]);

        $matricula->update($validated);

        return $matricula;
    }

    public function destroy($id)
    {
        $matricula = Matricula::findOrFail($id);
        $matricula->delete();

        return response()->json(['message' => 'Matrícula eliminada'], 200);
    }
}
