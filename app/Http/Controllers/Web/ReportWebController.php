<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class ReportWebController extends Controller
{
    public function gerarRelatorioLivrosPorAutor()
    {
        try {
            $dadosView = DB::table('View_Livros_Por_Autor')
                            ->orderBy('NomeAutor')
                            ->orderBy('TituloLivro')
                            ->get();

            if ($dadosView->isEmpty()) {
                return back()->with('warning', 'Não há dados para gerar o relatório.');
            }

            $livrosPorAutor = $dadosView->groupBy('CodAu')->map(function ($autorComLivros) {
                return [
                    'NomeAutor' => $autorComLivros->first()->NomeAutor,
                    'Livros' => $autorComLivros->map(function ($livro) {
                        return [
                            'TituloLivro' => $livro->TituloLivro,
                            'Editora' => $livro->Editora,
                            'Edicao' => $livro->Edicao,
                            'AnoPublicacao' => $livro->AnoPublicacao,
                            'Valor' => $livro->Valor,
                        ];
                    })->values()->all()
                ];
            })->values();

            $pdf = Pdf::loadView('web.relatorios.livros_por_autor', compact('livrosPorAutor'));

            return $pdf->download('relatorio_livros_por_autor.pdf');

        } catch (Exception $e) {
            return redirect()->route('home')->with('error', 'Erro ao gerar o relatório: ' . $e->getMessage());
        }
    }
}