<?php

namespace App\Domain\Financeiro\Empenho\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Empenho\Services\RelatorioRetencoesEfdReinf\RelatorioRetencoesEfdReinfService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RelatorioRetencoesEfdReinfController extends Controller
{
    public function emitirRelatorio(Request $request)
    {
        $service = new RelatorioRetencoesEfdReinfService();
        $service->setFiltrosRequest($request->all());
        return new DBJsonResponse($service->emitir(), 'Emissão das retenções do EFD-Reinf.');
    }
}
