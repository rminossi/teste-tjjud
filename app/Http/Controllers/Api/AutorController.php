<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAutorRequest;
use App\Http\Requests\UpdateAutorRequest;
use App\Models\Autor;
use Illuminate\Database\QueryException;

/**
 * @OA\Tag(
 *     name="Autores",
 *     description="Gerenciamento de autores"
 * )
 */
class AutorController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/autores",
     *     tags={"Autores"},
     *     summary="Listar autores",
     *
     *     @OA\Response(response=200, description="Lista de autores")
     * )
     */
    public function index()
    {
        try {
            $autores = Autor::orderBy('Nome')->paginate(15);

            return response()->json($autores);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar autores.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/autores",
     *     tags={"Autores"},
     *     summary="Criar autor",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"Nome"},
     *
     *             @OA\Property(property="Nome", type="string", example="Autor Teste")
     *         )
     *     ),
     *
     *     @OA\Response(response=201, description="Autor criado com sucesso"),
     *     @OA\Response(response=500, description="Erro ao criar autor")
     * )
     */
    public function store(StoreAutorRequest $request)
    {
        try {
            $autor = Autor::create($request->validated());

            return response()->json($autor, 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Erro ao salvar o autor no banco de dados.', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar o autor.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/autores/{codAu}",
     *     tags={"Autores"},
     *     summary="Exibir autor",
     *
     *     @OA\Parameter(name="codAu", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Autor encontrado"),
     *     @OA\Response(response=404, description="Autor não encontrado")
     * )
     */
    public function show(string $codAu)
    {
        try {
            $autor = Autor::findOrFail($codAu);

            return response()->json($autor);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Autor não encontrado.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar o autor.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/autores/{codAu}",
     *     tags={"Autores"},
     *     summary="Atualizar autor",
     *
     *     @OA\Parameter(name="codAu", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"Nome"},
     *
     *             @OA\Property(property="Nome", type="string", example="Autor Atualizado")
     *         )
     *     ),
     *
     *     @OA\Response(response=200, description="Autor atualizado com sucesso"),
     *     @OA\Response(response=404, description="Autor não encontrado")
     * )
     */
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar o autor.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/autores/{codAu}",
     *     tags={"Autores"},
     *     summary="Excluir autor",
     *
     *     @OA\Parameter(name="codAu", in="path", required=true, @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=204, description="Autor excluído com sucesso"),
     *     @OA\Response(response=404, description="Autor não encontrado")
     * )
     */
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
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir o autor.', 'error' => $e->getMessage()], 500);
        }
    }
}
