<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API TJJud",
 *     version="1.0",
 *     description="Documentação da API do sistema de gerenciamento de livros, autores e assuntos.",
 *     @OA\Contact(
 *         email="seu-email@exemplo.com",
 *         name="Seu Nome"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor local"
 * )
 */

abstract class Controller extends BaseController
{
}
