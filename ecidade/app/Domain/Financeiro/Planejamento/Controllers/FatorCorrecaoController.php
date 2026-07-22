<?php


namespace App\Domain\Financeiro\Planejamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Planejamento\Requests\FatorCorrecao\BuscaFatorCorrecaoRequest;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Projecao\PersisteFatorCorrecaoRequest;
use App\Domain\Financeiro\Planejamento\Services\FatorCorrecaoService;
use App\Http\Controllers\Controller;
use Exception;

class FatorCorrecaoController extends Controller
{
    /**
     * @var FatorCorrecaoService
     */
    private $service;

    public function __construct(FatorCorrecaoService $service)
    {
        $this->service = $service;
    }

    /**
     * Persiste os fatores de correção da despesa
     * @param PersisteFatorCorrecaoRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function salvarDespesa(PersisteFatorCorrecaoRequest $request)
    {
        $this->service->setRequestFatorCorrecao($request);
        $this->service->persistirFatorDespesa();
        return new DBJsonResponse([], 'Fator de correção salvo com sucesso.');
    }

    /**
     * @param PersisteFatorCorrecaoRequest $request
     * @return DBJsonResponse
     * @throws Exception
     */
    public function salvarReceita(PersisteFatorCorrecaoRequest $request)
    {
        $this->service->setRequestFatorCorrecao($request);
        $this->service->persistirFatorReceita();
        return new DBJsonResponse([], 'Fator de correção salvo com sucesso.');
    }

    public function index(BuscaFatorCorrecaoRequest $request)
    {
        return new DBJsonResponse($this->service->get($request->all()), '');
    }
}
