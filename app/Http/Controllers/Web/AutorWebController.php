<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use App\Http\Requests\StoreAutorRequest;
use App\Http\Requests\UpdateAutorRequest;
use Illuminate\Database\QueryException;
use Exception;

class AutorWebController extends Controller
{
    public function index()
    {
        $autores = Autor::orderBy('Nome')->paginate(10);
        return view('web.autores.index', compact('autores'));
    }

    public function create()
    {
        return view('web.autores.create');
    }

    public function store(StoreAutorRequest $request)
    {
        try {
            Autor::create($request->validated());
            return redirect()->route('autores.index')->with('success', 'Autor criado com sucesso!');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Erro ao salvar o autor no banco: ' . $e->getMessage());
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Erro ao criar o autor: ' . $e->getMessage());
        }
    }

    public function show(string $codAu)
    {
        try {
            $autor = Autor::findOrFail($codAu);
            return view('web.autores.show', compact('autor'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('autores.index')->with('error', 'Autor não encontrado.');
        }
    }

    public function edit(string $codAu)
    {
        try {
            $autor = Autor::findOrFail($codAu);
            return view('web.autores.edit', compact('autor'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('autores.index')->with('error', 'Autor não encontrado.');
        }
    }

    public function update(UpdateAutorRequest $request, string $codAu)
    {
        try {
            $autor = Autor::findOrFail($codAu);
            $autor->update($request->validated());
            return redirect()->route('autores.index')->with('success', 'Autor atualizado com sucesso!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('autores.index')->with('error', 'Autor não encontrado.');
        } catch (QueryException $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar o autor no banco: ' . $e->getMessage());
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar o autor: ' . $e->getMessage());
        }
    }

    public function destroy(string $codAu)
    {
        try {
            $autor = Autor::findOrFail($codAu);
            $autor->delete();
            return redirect()->route('autores.index')->with('success', 'Autor excluído com sucesso!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('autores.index')->with('error', 'Autor não encontrado.');
        } catch (QueryException $e) {
             if (str_contains($e->getMessage(), 'constraint violation')) {
                 return redirect()->route('autores.index')->with('error', 'Não é possível excluir o autor pois ele está associado a livros.');
            }
            return redirect()->route('autores.index')->with('error', 'Erro ao excluir o autor do banco: ' . $e->getMessage());
        } catch (Exception $e) {
            return redirect()->route('autores.index')->with('error', 'Erro ao excluir o autor: ' . $e->getMessage());
        }
    }
}