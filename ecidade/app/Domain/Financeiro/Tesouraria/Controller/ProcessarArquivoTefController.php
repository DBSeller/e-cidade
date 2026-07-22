<?php


namespace App\Domain\Financeiro\Tesouraria\Controller;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Tesouraria\Services\TefService;
use App\Http\Controllers\Controller;
use DBDate;
use Illuminate\Http\Request;

class ProcessarArquivoTefController extends Controller
{
    private $service;

    public function __construct(TefService $service)
    {
        $this->service = $service;
    }

    /**
     * Retorna as linhas ainda não processadas do TEF
     */
    public function index()
    {
        $linhas = $this->service->linhasNaoProcessadas();
        return new DBJsonResponse($linhas, 'Linhas para processar.');
    }

    public function store(Request $request)
    {
        $this->service->setDataLancamento(DBDate::createFromTimestamp($request->get('DB_datausu')));
        $this->service->processar($request->get('linhasTef'));

        return new DBJsonResponse([], 'Lançamentos executados.');
    }

    public function registrarInconsistente(Request $request)
    {
        $this->service->marcarVistoInconsistente($request->get('id'));

        return new DBJsonResponse([], 'Registro salvo como visualizado.');
    }
}
