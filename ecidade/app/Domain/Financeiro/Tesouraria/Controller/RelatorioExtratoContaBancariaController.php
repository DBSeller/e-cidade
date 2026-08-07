<?php

namespace App\Domain\Financeiro\Tesouraria\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Tesouraria\Services\Relatorios\ExtratoContaBancaria\RelatorioExtratoContaBancariaService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RelatorioExtratoContaBancariaController extends Controller
{
    public function extratoContaBancaria(Request $request)
    {
        $service = new RelatorioExtratoContaBancariaService();
        $service->setFiltrosRequest($request->all());
        return new DBJsonResponse($service->emitir(), 'Emissão do extrato da conta bancária');
    }
}
