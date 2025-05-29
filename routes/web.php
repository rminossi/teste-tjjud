<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\LivroWebController;
use App\Http\Controllers\Web\AutorWebController;
use App\Http\Controllers\Web\AssuntoWebController;
use App\Http\Controllers\Web\ReportWebController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::resource('livros', LivroWebController::class);
Route::resource('autores', AutorWebController::class);
Route::resource('assuntos', AssuntoWebController::class);

Route::get('/relatorio/livros-por-autor', [ReportWebController::class, 'gerarRelatorioLivrosPorAutor'])
    ->name('relatorios.livrosPorAutor');