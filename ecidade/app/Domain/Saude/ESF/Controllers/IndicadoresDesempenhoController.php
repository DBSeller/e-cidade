<?php

namespace App\Domain\Saude\ESF\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Saude\ESF\Factories\IndicadoresDesempenhoFactory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @package App\Domain\Saude\ESF\Controllers
 */
class IndicadoresDesempenhoController extends Controller
{
    public function relatorio(Request $request)
    {
        $service = IndicadoresDesempenhoFactory::getService($request->indicador)
            ->setPeriodoInicio(new \DateTime($request->periodoInicio))
            ->setPeriodoFim(new \DateTime($request->periodoFim))
            ->setUnidades($request->get('unidades', []));

        $pdf = $service->gerarRelatorio();

        return new DBJsonResponse($pdf->emitir(), 'Emitindo relatório.');
    }
}
