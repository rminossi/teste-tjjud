<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assunto;
use App\Http\Requests\StoreAssuntoRequest;
use App\Http\Requests\UpdateAssuntoRequest;
use Illuminate\Database\QueryException;
use Exception;

class AssuntoController extends Controller
{
    public function index()
    {
        try {
            $assuntos = Assunto::orderBy('Descricao')->paginate(15);
            return response()->json($assuntos);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar assuntos.', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreAssuntoRequest $request)
    {
        try {
            $assunto = Assunto::create($request->validated());
            return response()->json($assunto, 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Erro ao salvar o assunto no banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao criar o assunto.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $codAs)
    {
        try {
            $assunto = Assunto::findOrFail($codAs);
            return response()->json($assunto);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Assunto não encontrado.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar o assunto.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateAssuntoRequest $request, string $codAs)
    {
        try {
            $assunto = Assunto::findOrFail($codAs);
            $assunto->update($request->validated());
            return response()->json($assunto);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Assunto não encontrado para atualização.'], 404);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Erro ao atualizar o assunto no banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar o assunto.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $codAs)
    {
        try {
            $assunto = Assunto::findOrFail($codAs);
            $assunto->delete();
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Assunto não encontrado para exclusão.'], 404);
        } catch (QueryException $e) {
             if (str_contains($e->getMessage(), 'constraint violation')) {
                return response()->json(['message' => 'Não é possível excluir o assunto pois ele está associado a um ou mais livros.'], 409);
            }
            return response()->json(['message' => 'Erro ao excluir o assunto do banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao excluir o assunto.', 'error' => $e->getMessage()], 500);
        }
    }
}