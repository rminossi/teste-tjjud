<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LivroController;
use App\Http\Controllers\Api\AutorController;
use App\Http\Controllers\Api\AssuntoController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('livros', LivroController::class);
Route::apiResource('autores', AutorController::class);
Route::apiResource('assuntos', AssuntoController::class);