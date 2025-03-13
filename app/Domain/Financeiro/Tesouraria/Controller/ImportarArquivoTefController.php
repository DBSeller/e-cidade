<?php


namespace App\Domain\Financeiro\Tesouraria\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Tesouraria\Requests\ImportarTefRequest;
use App\Domain\Financeiro\Tesouraria\Services\ImportarTefService;
use App\Http\Controllers\Controller;

class ImportarArquivoTefController extends Controller
{
    public function store(ImportarTefRequest $request)
    {
        $service = new ImportarTefService($request->get('file'), $request->get('path'));
        $service->process();
        return new DBJsonResponse([], 'Arquivo importado com sucesso.');
    }
}
