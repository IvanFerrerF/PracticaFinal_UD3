<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Ruta básica para probar
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Rutas para los controladores
Route::apiResource('estudiantes', \App\Http\Controllers\EstudianteController::class);
Route::apiResource('cursos', \App\Http\Controllers\CursoController::class);
Route::apiResource('profesores', \App\Http\Controllers\ProfesorController::class);
Route::apiResource('matriculas', \App\Http\Controllers\MatriculaController::class);
Route::apiResource('asignaturas', \App\Http\Controllers\AsignaturaController::class);
Route::apiResource('evaluaciones', \App\Http\Controllers\EvaluacionController::class);
