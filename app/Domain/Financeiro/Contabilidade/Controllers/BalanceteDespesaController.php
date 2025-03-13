<?php

namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Requests\EmissaoBalancetesRequest;
use App\Domain\Financeiro\Contabilidade\Services\Relatorios\RelatorioBalanceteDespesaService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class BalanceteDespesaController extends Controller
{
    public function emitirPorComplemento(EmissaoBalancetesRequest $request)
    {
        $service = new RelatorioBalanceteDespesaService();
        $service->setFiltrosRequest($request->all());
        $files = $service->emitir();
        return new DBJsonResponse($files, 'Emissão do balancete da despesa.');
    }
}
