<?php


namespace App\Domain\Financeiro\Planejamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Planejamento\Requests\AlteraStatusRequest;
use App\Domain\Financeiro\Planejamento\Services\AlteraStatusService;
use App\Http\Controllers\Controller;

/**
 * Class SituacaoPlanejamento
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class StatusPlanejamentoController extends Controller
{
    /**
     * @param AlteraStatusRequest $request
     * @return DBJsonResponse
     */
    public function store(AlteraStatusRequest $request)
    {
        $service = new AlteraStatusService();
        $service->alterar($request->get('pl2_codigo'), $request->get('pl1_codigo'));
        return new DBJsonResponse([], "Situação do plano atualizada com sucesso.");
    }
}
