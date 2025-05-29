<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use App\Http\Requests\StoreAutorRequest;
use App\Http\Requests\UpdateAutorRequest;
use Illuminate\Database\QueryException;
use Exception;

class AutorController extends Controller
{
    public function index()
    {
        try {
            $autores = Autor::orderBy('Nome')->paginate(15);
            return response()->json($autores);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar autores.', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreAutorRequest $request)
    {
        try {
            $autor = Autor::create($request->validated());
            return response()->json($autor, 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Erro ao salvar o autor no banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao criar o autor.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $codAu)
    {
        try {
            $autor = Autor::findOrFail($codAu);
            return response()->json($autor);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Autor não encontrado.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar o autor.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateAutorRequest $request, string $codAu)
    {
        try {
            $autor = Autor::findOrFail($codAu);
            $autor->update($request->validated());
            return response()->json($autor);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Autor não encontrado para atualização.'], 404);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Erro ao atualizar o autor no banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar o autor.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $codAu)
    {
        try {
            $autor = Autor::findOrFail($codAu);
            $autor->delete();
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Autor não encontrado para exclusão.'], 404);
        } catch (QueryException $e) {
             if (str_contains($e->getMessage(), 'constraint violation')) {
                return response()->json(['message' => 'Não é possível excluir o autor pois ele está associado a um ou mais livros.'], 409);
            }
            return response()->json(['message' => 'Erro ao excluir o autor do banco de dados.', 'error' => $e->getMessage()], 500);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Erro ao excluir o autor.', 'error' => $e->getMessage()], 500);
        }
    }
}