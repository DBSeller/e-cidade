<?php

namespace App\Domain\Financeiro\Empenho\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Empenho\Requests\ConferenciaExtraOrcamentariaRequest;
use App\Domain\Financeiro\Empenho\Services\ConferenciaExtraOrcamentariaService;
use App\Http\Controllers\Controller;

class ConferenciaExtraOrcamentariaController extends Controller
{
    public function exportar(ConferenciaExtraOrcamentariaRequest $request)
    {
        $service = new ConferenciaExtraOrcamentariaService();
        return new DBJsonResponse($service->exportar($request->all()), 'Busca feita com sucesso.');
    }
}
