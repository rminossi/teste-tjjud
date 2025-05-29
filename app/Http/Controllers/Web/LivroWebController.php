<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;
use App\Http\Requests\StoreLivroRequest;
use App\Http\Requests\UpdateLivroRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;

class LivroWebController extends Controller
{
    public function index()
    {
        $livros = Livro::with(['autores', 'assuntos'])->orderBy('Titulo')->paginate(10);
        return view('web.livros.index', compact('livros'));
    }

    public function create()
    {
        $autores = Autor::orderBy('Nome')->get();
        $assuntos = Assunto::orderBy('Descricao')->get();
        return view('web.livros.create', compact('autores', 'assuntos'));
    }

    public function store(StoreLivroRequest $request)
    {
        DB::beginTransaction();
        try {
            $dadosLivro = $request->only(['Titulo', 'Editora', 'Edicao', 'AnoPublicacao', 'Valor']);
            $livro = Livro::create($dadosLivro);

            $livro->autores()->sync($request->input('autores', []));
            $livro->assuntos()->sync($request->input('assuntos', []));

            DB::commit();
            return redirect()->route('livros.index')->with('success', 'Livro criado com sucesso!');
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao salvar o livro no banco: ' . $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao criar o livro: ' . $e->getMessage());
        }
    }

    public function show(string $codL)
    {
        try {
            $livro = Livro::with(['autores', 'assuntos'])->findOrFail($codL);
            return view('web.livros.show', compact('livro'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('livros.index')->with('error', 'Livro não encontrado.');
        }
    }

    public function edit(string $codL)
    {
        try {
            $livro = Livro::with(['autores', 'assuntos'])->findOrFail($codL);
            $autores = Autor::orderBy('Nome')->get();
            $assuntos = Assunto::orderBy('Descricao')->get();
            return view('web.livros.edit', compact('livro', 'autores', 'assuntos'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('livros.index')->with('error', 'Livro não encontrado.');
        }
    }

    public function update(UpdateLivroRequest $request, string $codL)
    {
        DB::beginTransaction();
        try {
            $livro = Livro::findOrFail($codL);
            $dadosLivro = $request->only(['Titulo', 'Editora', 'Edicao', 'AnoPublicacao', 'Valor']);
            $livro->update($dadosLivro);

            $livro->autores()->sync($request->input('autores', []));
            $livro->assuntos()->sync($request->input('assuntos', []));

            DB::commit();
            return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('livros.index')->with('error', 'Livro não encontrado.');
        } catch (QueryException $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar o livro no banco: ' . $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar o livro: ' . $e->getMessage());
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
            return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect()->route('livros.index')->with('error', 'Livro não encontrado.');
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('livros.index')->with('error', 'Erro ao excluir o livro do banco: ' . $e->getMessage());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('livros.index')->with('error', 'Erro ao excluir o livro: ' . $e->getMessage());
        }
    }
}