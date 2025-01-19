<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profesor;

class ProfesorController extends Controller
{
    public function index()
    {
        return Profesor::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|email|unique:profesores,email',
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'required|string|max:255',
        ]);

        return Profesor::create($validated);
    }

    public function show($id)
    {
        return Profesor::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $profesor = Profesor::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'string|max:255',
            'apellidos' => 'string|max:255',
            'email' => 'email|unique:profesores,email,' . $id,
            'telefono' => 'nullable|string|max:20',
            'especialidad' => 'string|max:255',
        ]);

        $profesor->update($validated);

        return $profesor;
    }

    public function destroy($id)
    {
        $profesor = Profesor::findOrFail($id);
        $profesor->delete();

        return response()->json(['message' => 'Profesor eliminado'], 200);
    }
}
