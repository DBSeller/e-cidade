<?php


namespace App\Domain\Financeiro\Orcamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Orcamento\Requests\Cadastro\ComplementoExcluirRequest;
use App\Domain\Financeiro\Orcamento\Requests\Cadastro\ComplementoSalvarRequest;
use App\Domain\Financeiro\Orcamento\Services\ComplementoService;
use App\Http\Controllers\Controller;
use Exception;

/**
 * Class ComplementoController
 * @package App\Domain\Financeiro\Orcamento\Controllers
 */
class ComplementoController extends Controller
{
    /**
     * @var ComplementoService
     */
    private $service;

    public function __construct(ComplementoService $service)
    {
        $this->service = $service;
    }

    public function get()
    {
        return new DBJsonResponse($this->service->getAll()->toArray());
    }

    /**
     * @param ComplementoSalvarRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function salvar(ComplementoSalvarRequest $request)
    {
        $complemento = $this->service->salvar($request);
        return new DBJsonResponse($complemento, "Complemento salvo com sucesso.");
    }

    public function excluir(ComplementoExcluirRequest $request)
    {
        $this->service->excluir($request->get('codigo'));
        return new DBJsonResponse([], "Complemento excluído com sucesso.");
    }
}
