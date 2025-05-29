<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Livros por Autor</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; margin: 20px; color: #333; } /* DejaVu Sans for UTF-8 PDF */
        h1 { text-align: center; color: #444; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom:10px;}
        .author-section { margin-bottom: 25px; page-break-inside: avoid; }
        .author-name { font-size: 14px; font-weight: bold; color: #0056b3; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; font-size:11px; }
        .no-data { text-align: center; font-style: italic; color: #777; padding: 20px; }
        .footer { text-align: center; font-size: 9px; color: #777; position: fixed; bottom: 0px; width:100%; }
        @page { margin: 40px 25px; }
    </style>
</head>
<body>
    <h1>Relatório de Livros por Autor</h1> @if(empty($livrosPorAutor))
        <p class="no-data">Nenhum dado encontrado para este relatório.</p>
    @else
        @foreach ($livrosPorAutor as $autorData)
            <div class="author-section">
                <div class="author-name">Autor: {{ $autorData['NomeAutor'] }}</div> @if(empty($autorData['Livros']))
                    <p>Nenhum livro encontrado para este autor.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Título do Livro</th>
                                <th>Editora</th>
                                <th>Edição</th>
                                <th>Ano</th>
                                <th>Valor (R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($autorData['Livros'] as $livro)
                                <tr>
                                    <td>{{ $livro['TituloLivro'] }}</td>
                                    <td>{{ $livro['Editora'] }}</td>
                                    <td>{{ $livro['Edicao'] }}</td>
                                    <td>{{ $livro['AnoPublicacao'] }}</td>
                                    <td>{{ number_format($livro['Valor'], 2, ',', '.') }}</td> </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        @endforeach
    @endif
    <div class="footer">
        Gerado em: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>