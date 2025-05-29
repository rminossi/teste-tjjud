<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Assunto;
use App\Http\Requests\StoreAssuntoRequest;
use App\Http\Requests\UpdateAssuntoRequest;
use Illuminate\Database\QueryException;
use Exception;

class AssuntoWebController extends Controller
{
    public function index()
    {
        $assuntos = Assunto::orderBy('Descricao')->paginate(10);
        return view('web.assuntos.index', compact('assuntos'));
    }

    public function create()
    {
        return view('web.assuntos.create');
    }

    public function store(StoreAssuntoRequest $request)
    {
        try {
            Assunto::create($request->validated());
            return redirect()->route('assuntos.index')->with('success', 'Assunto criado com sucesso!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Erro ao salvar o assunto no banco: ' . $e->getMessage());
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Erro ao criar o assunto: ' . $e->getMessage());
        }
    }

    public function show(string $codAs)
    {
        try {
            $assunto = Assunto::findOrFail($codAs);
            return view('web.assuntos.show', compact('assunto'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('assuntos.index')->with('error', 'Assunto não encontrado.');
        }
    }

    public function edit(string $codAs)
    {
        try {
            $assunto = Assunto::findOrFail($codAs);
            return view('web.assuntos.edit', compact('assunto'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('assuntos.index')->with('error', 'Assunto não encontrado.');
        }
    }

    public function update(UpdateAssuntoRequest $request, string $codAs)
    {
        try {
            $assunto = Assunto::findOrFail($codAs);
            $assunto->update($request->validated());
            return redirect()->route('assuntos.index')->with('success', 'Assunto atualizado com sucesso!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('assuntos.index')->with('error', 'Assunto não encontrado.');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar o assunto no banco: ' . $e->getMessage());
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar o assunto: ' . $e->getMessage());
        }
    }

    public function destroy(string $codAs)
    {
        try {
            $assunto = Assunto::findOrFail($codAs);
            $assunto->delete();
            return redirect()->route('assuntos.index')->with('success', 'Assunto excluído com sucesso!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('assuntos.index')->with('error', 'Assunto não encontrado.');
        } catch (QueryException $e) {
            if (str_contains($e->getMessage(), 'constraint violation')) {
                 return redirect()->route('assuntos.index')->with('error', 'Não é possível excluir o assunto pois ele está associado a livros.');
            }
            return redirect()->route('assuntos.index')->with('error', 'Erro ao excluir o assunto do banco: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->route('assuntos.index')->with('error', 'Erro ao excluir o assunto: ' . $e->getMessage());
        }
    }
}