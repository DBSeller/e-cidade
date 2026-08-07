<?php

namespace App\Domain\Tributario\Arrecadacao\Controllers;

use App\Domain\Core\Base\Http\Response\DBJsonResponse;
use App\Domain\Tributario\Arrecadacao\Repositories\OperacoesrealizadastefRepository;
use App\Domain\Tributario\Arrecadacao\Requests\TEF\AlterarOperacaoRequest;
use App\Domain\Tributario\Arrecadacao\Requests\TEF\ComprovanteDesfazimentoRequest;
use App\Domain\Tributario\Arrecadacao\Requests\TEF\ConfirmarDesfazerOperacaoRequest;
use App\Domain\Tributario\Arrecadacao\Requests\TEF\IncluirOperacaoRequest;
use App\Domain\Tributario\Arrecadacao\Requests\TEF\PendentesRequest;
use App\Domain\Tributario\Arrecadacao\Services\ComprovanteDesfazimentoTefService;
use App\Domain\Tributario\Arrecadacao\Services\RelatorioPendentesTefService;
use App\Domain\Tributario\Arrecadacao\Services\TEFBaixaBancoService;
use App\Domain\Tributario\Arrecadacao\Requests\TEF\TEFBaixaBancoAutomaticaRequest;
use App\Domain\Tributario\Arrecadacao\Services\TEFService;
use App\Http\Controllers\Controller;

class TEFController extends Controller
{
    public function baixaAutomaticaDebito(
        TEFBaixaBancoAutomaticaRequest $request,
        TEFBaixaBancoService $tefBaixaBancoService
    ) {
        $tefBaixaBancoService->setNumpre($request->numpre)
                             ->setValor($request->valor)
                             ->setConta($request->conta)
                             ->baixaAutomaticaDebito();

        return new DBJsonResponse(null, "Débito(s) baixado(s) com sucesso!");
    }

    public function incluirOperacao(IncluirOperacaoRequest $request, TEFService $tefService)
    {
        $sequencial = $tefService->salvarOperacao((object) $request->all());

        return new DBJsonResponse(["sequencial" => $sequencial], "Operação salva com sucesso!");
    }

    public function alterarOperacao(AlterarOperacaoRequest $request, TEFService $tefService)
    {
        $sequencial = $tefService->salvarOperacao((object) $request->all());

        return new DBJsonResponse(["sequencial" => $sequencial], "Operação salva com sucesso!");
    }

    public function confirmarOperacao(ConfirmarDesfazerOperacaoRequest $request, TEFService $tefService)
    {
        $sequencial = $tefService->confirmarOperacao((object) $request->all());

        return new DBJsonResponse(["sequencial" => $sequencial], "Operação confirmada com sucesso!");
    }

    public function desfazerOperacao(ConfirmarDesfazerOperacaoRequest $request, TEFService $tefService)
    {
        $sequencial = $tefService->desfazerOperacao((object) $request->all());

        return new DBJsonResponse(["sequencial" => $sequencial], "Operação desfeita com sucesso!");
    }

    public function comprovanteDesfazimento(ComprovanteDesfazimentoRequest $request)
    {
        $service = new ComprovanteDesfazimentoTefService();
        $service->setNumnov($request->numnov);
        $service->setGrupo($request->grupo);
        $service->setMostraArquivo(false);
        $service->setRetornaBase64(false);
        $service->gerar();

        return new DBJsonResponse([
            "arquivo" => $service->getArquivo()
        ]);
    }

    public function relatorioPendentes(PendentesRequest $request)
    {
        $service = new RelatorioPendentesTefService();
        $service->setDataInicio($request->dataInicio);
        $service->setDataFim($request->dataFim);
        $service->setTerminal($request->terminal);
        $service->setMostraArquivo(false);
        $service->setRetornaBase64(false);
        $service->gerar();

        return new DBJsonResponse([
            "arquivo" => $service->getArquivo()
        ]);
    }

    public function operacoesPendentes(PendentesRequest $request, TEFService $service)
    {
        $pendentes = $service->operacoesPendentes((object) $request->all());

        return new DBJsonResponse([
            "pendentes" => $pendentes
        ]);
    }
}
