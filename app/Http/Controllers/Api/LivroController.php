<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Livro;
use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;

class LivroController extends Controller
{
    public function index()
    {
        try {
            $livros = Livro::with(['autores', 'assuntos'])->orderBy('Titulo')->paginate(15);
            return response()->json($livros);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar livros.', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(StoreLivroRequest $request)
    {
        DB::beginTransaction();
        try {
            $dadosLivro = $request->only(['Titulo', 'Editora', 'Edicao', 'AnoPublicacao', 'Valor']);
            $valor = str_replace(',', '.', $request->Valor);
            $dadosLivro['Valor'] = (float)$valor * 100;
            $livro = Livro::create($dadosLivro);

            if ($request->has('autores')) {
                $livro->autores()->sync($request->input('autores'));
            }
            if ($request->has('assuntos')) {
                $livro->assuntos()->sync($request->input('assuntos'));
            }

            DB::commit();
            return response()->json($livro->load(['autores', 'assuntos']), 201);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao salvar o livro no banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao criar o livro.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $codL)
    {
        try {
            $livro = Livro::with(['autores', 'assuntos'])->findOrFail($codL);
            return response()->json($livro);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Livro não encontrado.'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Erro ao buscar o livro.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateLivroRequest $request, string $codL)
    {
        DB::beginTransaction();
        try {
            $livro = Livro::findOrFail($codL);
            $dadosLivro = $request->only(['Titulo', 'Editora', 'Edicao', 'AnoPublicacao', 'Valor']);
            $valor = str_replace(',', '.', $request->Valor);
            $dadosLivro['Valor'] = (float)$valor * 100;
            $livro->update($dadosLivro);

            if ($request->has('autores')) {
                $livro->autores()->sync($request->input('autores'));
            }
            if ($request->has('assuntos')) {
                $livro->assuntos()->sync($request->input('assuntos'));
            }

            DB::commit();
            return response()->json($livro->load(['autores', 'assuntos']));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Livro não encontrado para atualização.'], 404);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao atualizar o livro no banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao atualizar o livro.', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $codL)
    {
        DB::beginTransaction();
        try {
            $livro = Livro::findOrFail($codL);
            $livro->autores()->detach();
            $livro->assuntos()->detach();
            $livro->delete();
            DB::commit();
            return response()->json(null, 204);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Livro não encontrado para exclusão.'], 404);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao excluir o livro do banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao excluir o livro.', 'error' => $e->getMessage()], 500);
        }
    }
}