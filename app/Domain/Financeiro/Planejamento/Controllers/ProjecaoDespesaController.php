<?php


namespace App\Domain\Financeiro\Planejamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Planejamento\Requests\Procedimentos\Projecao\Despesa\CalculaProjecaoRequest;
use App\Domain\Financeiro\Planejamento\Services\ProjecaoDespesaService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

/**
 * Class ProjecaoDespesa
 * @package App\Domain\Financeiro\Planejamento\Controllers
 */
class ProjecaoDespesaController extends Controller
{
    /**
     * @param CalculaProjecaoRequest $request
     * @param ProjecaoDespesaService $service
     * @throws Exception
     */
    public function calcular(CalculaProjecaoRequest $request, ProjecaoDespesaService $service)
    {
        $service->porRequest($request)->calcular();
    }

    /**
     * @param CalculaProjecaoRequest $request
     * @param ProjecaoDespesaService $service
     * @return DBJsonResponse
     * @throws Exception
     */
    public function projecao(CalculaProjecaoRequest $request, ProjecaoDespesaService $service)
    {
        $projecao = $service->porRequest($request)->getProjecao();
        return new DBJsonResponse($projecao, "Dados da projeção");
    }

    /**
     * Salva a projeção
     * Obs, da forma como esta se alterar alguma coisa no front, pode quebrar o back
     *
     * @param Request $request
     */
    public function salvarProjecao(Request $request, ProjecaoDespesaService $service)
    {
        $service->salvarPorResquest($request);

        $projecao = str_replace('\"', '"', $request->get('projecao'));

        $projecao = \JSON::create()->parse($projecao);
    }
}
