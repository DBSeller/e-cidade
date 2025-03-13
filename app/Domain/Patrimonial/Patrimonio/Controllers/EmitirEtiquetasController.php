<?php

namespace App\Domain\Patrimonial\Patrimonio\Controllers;

use App\Domain\Patrimonial\Patrimonio\Repositories\BensRepository;
use App\Domain\Patrimonial\Patrimonio\Services\EmitirEtiquetasService;
use Exception;
use App\Http\Controllers\Controller;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @package App\Domain\Patrimonial\Patrimonio\Controllers
 */
class EmitirEtiquetasController extends Controller
{
    public function emitirEtiquetas(Request $request)
    {
        $rule = [
            'codigos' => ['required', 'array'],
            'codigos.*' => 'integer'
        ];

        $mensagem = [
            'codigos.*.integer' => 'Os valores do array devem ser do tipo inteiro.',
        ];

        validaRequest($request->all(), $rule, $mensagem);

        $repository = new BensRepository();
        $service = new EmitirEtiquetasService();

        $pdf = $service->gerarEmicaoEtiquetas($repository->findByRequest($request), $request->modelo);
        return new DBJsonResponse($pdf->gerar(), 'Emitindo PDF');
    }
}
