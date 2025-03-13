<?php

namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Requests\ExportarRelatorioTCERequest;
use App\Domain\Financeiro\Contabilidade\Services\RelatorioTCEService;
use App\Http\Controllers\Controller;

class RelatorioTCEController extends Controller
{
    public function exportar(ExportarRelatorioTCERequest $request)
    {
        $service = new RelatorioTCEService();
        return new DBJsonResponse($service->exportar($request->all()), 'Processamento concluído');
    }

    public function buscar(ExportarRelatorioTCERequest $request)
    {
        $service = new RelatorioTCEService();
        return new DBJsonResponse($service->buscar($request->all()), 'Processamento concluído');
    }
}
