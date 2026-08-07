<?php

namespace App\Domain\Financeiro\Contabilidade\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Financeiro\Contabilidade\Requests\ManutencaoContaCorrenteRequest;
use App\Domain\Financeiro\Contabilidade\Services\ContaCorrentePcaspService;
use App\Http\Controllers\Controller;

class PcaspContaCorrenteController extends Controller
{
    public function buscarPorPcasp($codcon, $exercicio)
    {
        $service = new ContaCorrentePcaspService();
        $correnteVinculadas = $service->buscarContaCorrenteVinculadas(
            $codcon,
            $exercicio
        );
        return new DBJsonResponse($correnteVinculadas, 'Contas correntes encontradas.');
    }

    public function adicionar(ManutencaoContaCorrenteRequest $request)
    {
        $service = new ContaCorrentePcaspService();

        $service->adicionarContaCorrente(
            $request->get('codcon'),
            $request->get('exercicio'),
            $request->get('contaCorrente')
        );

        $correnteVinculadas = $service->buscarContaCorrenteVinculadas(
            $request->get('codcon'),
            $request->get('exercicio')
        );
        return new DBJsonResponse($correnteVinculadas, 'Contas correntes encontradas.');
    }

    public function remover(ManutencaoContaCorrenteRequest $request)
    {
        $service = new ContaCorrentePcaspService();

        $service->removerContaCorrente(
            $request->get('codcon'),
            $request->get('exercicio'),
            $request->get('contaCorrente')
        );

        $correnteVinculadas = $service->buscarContaCorrenteVinculadas(
            $request->get('codcon'),
            $request->get('exercicio')
        );
        return new DBJsonResponse($correnteVinculadas, 'Contas correntes encontradas.');
    }
}
