<?php

namespace App\Domain\RecursosHumanos\Pessoal\Controller\Jetom;

use App\Domain\RecursosHumanos\Pessoal\Requests\Jetom\PontoMensal\ImportarPontoRequest;
use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\RecursosHumanos\Pessoal\Services\Jetom\ImportarPontoService as JetomImportarPontoService;
use App\Http\Controllers\Controller;

class ArquivoPontoController extends Controller
{
    public function importar(ImportarPontoRequest $request)
    {
        $service = new JetomImportarPontoService($request->all());
        $service->importar();
        return new DBJsonResponse([], 'Processamento concluído');
    }
}
