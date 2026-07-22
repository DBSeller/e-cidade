<?php

namespace App\Domain\Patrimonial\Licitacoes\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Patrimonial\Licitacoes\Requests\ImportarTramitaRequest;
use App\Domain\Patrimonial\Licitacoes\Services\ImportarLicitacoesTramitaService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TramitaController extends Controller
{
    public function importar(ImportarTramitaRequest $request)
    {
        $service = new ImportarLicitacoesTramitaService();
        $service->importar($request->all());
        $files = $service->log();

        return new DBJsonResponse($files, 'Resumo da importação arquivo Tramita');
    }
}
