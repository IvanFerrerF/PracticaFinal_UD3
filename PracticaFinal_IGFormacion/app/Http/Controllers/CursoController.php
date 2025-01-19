<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;

class CursoController extends Controller
{
    public function index()
    {
        return Curso::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'duracion' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        return Curso::create($validated);
    }

    public function show($id)
    {
        return Curso::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'string|max:255',
            'descripcion' => 'string',
            'duracion' => 'integer|min:1',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date|after:fecha_inicio',
        ]);

        $curso->update($validated);

        return $curso;
    }

    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();

        return response()->json(['message' => 'Curso eliminado'], 200);
    }
}
