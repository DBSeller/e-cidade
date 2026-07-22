<?php

namespace App\Domain\Financeiro\Orcamento\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Orcamento\Requests\RecalcularCronogramaDesembolsoDespesaRequest;
use App\Http\Controllers\Controller;
use ECidade\Enum\Financeiro\Orcamento\BaseCalculoEnum;
use ECidade\Financeiro\Orcamento\Service\AcompanhamentoDesembolsoReceitaService;
use Illuminate\Http\Request;

class AcompanhamentoDesembolsoReceitaController extends Controller
{

    public function baseCalculo()
    {
        return new DBJsonResponse(BaseCalculoEnum::toArrayWithNames(), '');
    }

    public function buscar($exercicio)
    {
        $service = new AcompanhamentoDesembolsoReceitaService();
        $dados = $service->buscarCronograma($exercicio);
        return new DBJsonResponse($dados, '');
    }

    public function salvarEstimativa(Request $request)
    {
        $metasArrecadacao = parseStringJson($request->get('metasArrecacadacao'));

        if (!is_array($metasArrecadacao) || empty($metasArrecadacao)) {
            throw new \Exception('Revise os dados enviados.', 403);
        }
        $service = new AcompanhamentoDesembolsoReceitaService();
        $service->atualizarEstimativas($metasArrecadacao);
        return new DBJsonResponse([], 'Metas de arrecadação salva.');
    }

    public function recalcular(RecalcularCronogramaDesembolsoDespesaRequest $request)
    {
        $service = new AcompanhamentoDesembolsoReceitaService();
        $cronogramasRetorno = $service->recalcular($request->all());
        return new DBJsonResponse($cronogramasRetorno, 'Estimativa recalculada com sucesso.');
    }
}
