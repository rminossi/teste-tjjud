<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use App\Models\Livro;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Tag(
 *     name="Livros",
 *     description="Gerenciamento de livros"
 * )
 */
class LivroController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/livros",
     *     tags={"Livros"},
     *     summary="Listar livros",
     *
     *     @OA\Response(response=200, description="Lista de livros")
     * )
     */
    public function index()
    {
        try {
            $livros = Livro::with(['autores', 'assuntos'])->orderBy('Titulo')->paginate(15);

            return response()->json($livros);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar livros.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/livros",
     *     tags={"Livros"},
     *     summary="Criar livro",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"Titulo","Editora","Edicao","AnoPublicacao","Valor","autores","assuntos"},
     *
     *             @OA\Property(property="Titulo", type="string", example="Livro Teste"),
     *             @OA\Property(property="Editora", type="string", example="Editora Teste"),
     *             @OA\Property(property="Edicao", type="integer", example=1),
     *             @OA\Property(property="AnoPublicacao", type="string", example="2024"),
     *             @OA\Property(property="Valor", type="number", format="float", example=29.90),
     *             @OA\Property(property="autores", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="assuntos", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Livro criado com sucesso"),
     *     @OA\Response(response=500, description="Erro ao criar livro")
     * )
     */
    public function store(StoreLivroRequest $request)
    {
        DB::beginTransaction();
        try {
            $dadosLivro = $request->only(['Titulo', 'Editora', 'Edicao', 'AnoPublicacao', 'Valor']);
            $valor = str_replace(',', '.', $request->Valor);
            $dadosLivro['Valor'] = (float) $valor * 100;
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
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Erro ao criar o livro.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/livros/{codL}",
     *     tags={"Livros"},
     *     summary="Exibir livro",
     *
     *     @OA\Parameter(name="codL", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Livro encontrado"),
     *     @OA\Response(response=404, description="Livro não encontrado")
     * )
     */
    public function show(string $codL)
    {
        try {
            $livro = Livro::with(['autores', 'assuntos'])->findOrFail($codL);

            return response()->json($livro);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Livro não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar o livro.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/livros/{codL}",
     *     tags={"Livros"},
     *     summary="Atualizar livro",
     *
     *     @OA\Parameter(name="codL", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"Titulo","Editora","Edicao","AnoPublicacao","Valor","autores","assuntos"},
     *
     *             @OA\Property(property="Titulo", type="string", example="Livro Atualizado"),
     *             @OA\Property(property="Editora", type="string", example="Editora Nova"),
     *             @OA\Property(property="Edicao", type="integer", example=2),
     *             @OA\Property(property="AnoPublicacao", type="string", example="2023"),
     *             @OA\Property(property="Valor", type="number", format="float", example=49.90),
     *             @OA\Property(property="autores", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="assuntos", type="array", @OA\Items(type="integer"))
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Livro atualizado com sucesso"),
     *     @OA\Response(response=404, description="Livro não encontrado")
     * )
     */
    public function update(UpdateLivroRequest $request, string $codL)
    {
        DB::beginTransaction();
        try {
            $livro = Livro::findOrFail($codL);
            $dadosLivro = $request->only(['Titulo', 'Editora', 'Edicao', 'AnoPublicacao', 'Valor']);
            $valor = str_replace(',', '.', $request->Valor);
            $dadosLivro['Valor'] = (float) $valor * 100;
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
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Erro ao atualizar o livro.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/livros/{codL}",
     *     tags={"Livros"},
     *     summary="Excluir livro",
     *
     *     @OA\Parameter(name="codL", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=204, description="Livro excluído com sucesso"),
     *     @OA\Response(response=404, description="Livro não encontrado")
     * )
     */
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
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Erro ao excluir o livro.', 'error' => $e->getMessage()], 500);
        }
    }
}
