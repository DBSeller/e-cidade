<?php

namespace App\Domain\Financeiro\Orcamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Orcamento\Requests\RecalcularCronogramaDesembolsoDespesaRequest;
use App\Domain\Financeiro\Orcamento\Requests\SalvarCronogramaDespesaRequest;
use App\Http\Controllers\Controller;
use ECidade\Enum\Financeiro\Orcamento\BaseCalculoDespesaEnum;
use ECidade\Financeiro\Orcamento\Service\AcompanhamentoDesembolsoDespesaService;

class AcompanhamentoDesembolsoDespesaController extends Controller
{
    public function baseCalculo()
    {
        return new DBJsonResponse(BaseCalculoDespesaEnum::toArrayWithNames(), '');
    }

    public function buscar($exercicio)
    {
        $service = new AcompanhamentoDesembolsoDespesaService();
        $dados = $service->buscarCronograma($exercicio);
        return new DBJsonResponse($dados, '');
    }

    public function salvarEstimativa(SalvarCronogramaDespesaRequest $request)
    {
        $service = new AcompanhamentoDesembolsoDespesaService();
        $service->atualizarEstimativa($request->all());
        return new DBJsonResponse([], 'Estimativa atualizada com sucesso.');
    }

    /**
     * Esse metodo só retorna os dados do cronograma de desembolso... que é o suficiente para atualizar o frontend
     * @param RecalcularCronogramaDesembolsoDespesaRequest $request
     * @return DBJsonResponse
     * @throws \Exception
     */
    public function recalcular(RecalcularCronogramaDesembolsoDespesaRequest $request)
    {
        $service = new AcompanhamentoDesembolsoDespesaService();
        $cronogramasRetorno = $service->recalcular($request->all());

        return new DBJsonResponse($cronogramasRetorno, 'Estimativa recalculada com sucesso.');
    }
}
