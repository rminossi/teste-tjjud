<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssuntoRequest;
use App\Http\Requests\UpdateAssuntoRequest;
use App\Models\Assunto;
use Illuminate\Database\QueryException;

/**
 * @OA\Tag(
 *     name="Assuntos",
 *     description="Gerenciamento de assuntos"
 * )
 */
class AssuntoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/assuntos",
     *     tags={"Assuntos"},
     *     summary="Listar assuntos",
     *
     *     @OA\Response(response=200, description="Lista de assuntos")
     * )
     */
    public function index()
    {
        try {
            $assuntos = Assunto::orderBy('Descricao')->paginate(15);

            return response()->json($assuntos);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar assuntos.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/assuntos",
     *     tags={"Assuntos"},
     *     summary="Criar assunto",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"Descricao"},
     *
     *             @OA\Property(property="Descricao", type="string", example="Assunto Teste")
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Assunto criado com sucesso"),
     *     @OA\Response(response=500, description="Erro ao criar assunto")
     * )
     */
    public function store(StoreAssuntoRequest $request)
    {
        try {
            $assunto = Assunto::create($request->validated());

            return response()->json($assunto, 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Erro ao salvar o assunto no banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar o assunto.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/assuntos/{codAs}",
     *     tags={"Assuntos"},
     *     summary="Exibir assunto",
     *
     *     @OA\Parameter(name="codAs", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Assunto encontrado"),
     *     @OA\Response(response=404, description="Assunto não encontrado")
     * )
     */
    public function show(string $codAs)
    {
        try {
            $assunto = Assunto::findOrFail($codAs);

            return response()->json($assunto);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Assunto não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar o assunto.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/assuntos/{codAs}",
     *     tags={"Assuntos"},
     *     summary="Atualizar assunto",
     *
     *     @OA\Parameter(name="codAs", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"Descricao"},
     *
     *             @OA\Property(property="Descricao", type="string", example="Assunto Atualizado")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Assunto atualizado com sucesso"),
     *     @OA\Response(response=404, description="Assunto não encontrado")
     * )
     */
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar o assunto.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/assuntos/{codAs}",
     *     tags={"Assuntos"},
     *     summary="Excluir assunto",
     *
     *     @OA\Parameter(name="codAs", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=204, description="Assunto excluído com sucesso"),
     *     @OA\Response(response=404, description="Assunto não encontrado")
     * )
     */
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir o assunto.', 'error' => $e->getMessage()], 500);
        }
    }
}
