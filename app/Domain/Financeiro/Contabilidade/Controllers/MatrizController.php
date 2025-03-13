<?php

namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Requests\MSC\ImportarMatrizRequest;
use App\Domain\Financeiro\Contabilidade\Services\ImportarSaldosMatrizService;
use App\Http\Controllers\Controller;

class MatrizController extends Controller
{
    public function importar(ImportarMatrizRequest $request)
    {
        $service = new ImportarSaldosMatrizService();
        $service->importar($request->all());

        return new DBJsonResponse([], 'Processamento concluído');
    }
}
