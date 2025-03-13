<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use App\Domain\Tributario\Arrecadacao\Models\Operacoesrealizadastef;
use App\Domain\Tributario\Arrecadacao\Repositories\OperacoesrealizadastefRepository;

final class TEFService
{
    public function salvarOperacao($oDados)
    {
        $operacoesrealizadastef = new Operacoesrealizadastef();

        if (!empty($oDados->sequencial)) {
            $operacoesrealizadastef = (new OperacoesrealizadastefRepository())->getBySequencial($oDados->sequencial);
        }

        if (isset($oDados->numnov) && !empty($oDados->numnov)) {
            $operacoesrealizadastef->setNumnov($oDados->numnov);
        }

        if (isset($oDados->nsu) && !empty($oDados->nsu)) {
            $operacoesrealizadastef->setNsu($oDados->nsu);
        }

        if (isset($oDados->valor) && !empty($oDados->valor)) {
            $operacoesrealizadastef->setValor($oDados->valor);
        }

        if (isset($oDados->operacaotef) && !empty($oDados->operacaotef)) {
            $operacoesrealizadastef->setOperacaotef($oDados->operacaotef);
        }

        if (isset($oDados->bandeira) && !empty($oDados->bandeira)) {
            $operacoesrealizadastef->setBandeira($oDados->bandeira);
        }

        if (isset($oDados->parcela) && !empty($oDados->parcela)) {
            $operacoesrealizadastef->setParcela($oDados->parcela);
        }

        if (isset($oDados->dataoperacao) && !empty($oDados->dataoperacao)) {
            $operacoesrealizadastef->setDataoperacao($oDados->dataoperacao);
        } else {
            if (empty($operacoesrealizadastef->getDataoperacao())) {
                $operacoesrealizadastef->setDataoperacao("now()");
            }
        }

        if (isset($oDados->confirmado) && !empty($oDados->confirmado)) {
            $operacoesrealizadastef->setConfirmado($oDados->confirmado);
        }

        if (isset($oDados->mensagemretorno) && !empty($oDados->mensagemretorno)) {
            $operacoesrealizadastef->setMensagemretorno($oDados->mensagemretorno);
        }

        if (isset($oDados->desfeito) && !empty($oDados->desfeito)) {
            $operacoesrealizadastef->setDesfeito($oDados->desfeito);
        }

        if (isset($oDados->codigoaprovacao) && !empty($oDados->codigoaprovacao)) {
            $operacoesrealizadastef->setCodigoaprovacao($oDados->codigoaprovacao);
        }

        if (isset($oDados->nsuautorizadora) && !empty($oDados->nsuautorizadora)) {
            $operacoesrealizadastef->setNsuautorizadora($oDados->nsuautorizadora);
        }

        if (isset($oDados->concluidobaixabanco) && !empty($oDados->concluidobaixabanco)) {
            $operacoesrealizadastef->setConcluidobaixabanco($oDados->concluidobaixabanco);
        }

        if (isset($oDados->cartao) && !empty($oDados->cartao)) {
            $operacoesrealizadastef->setCartao($oDados->cartao);
        }

        if (isset($oDados->retorno) && !empty($oDados->retorno)) {
            $operacoesrealizadastef->setRetorno($oDados->retorno);
        }

        if (isset($oDados->grupo) && !empty($oDados->grupo)) {
            $operacoesrealizadastef->setGrupo($oDados->grupo);
        }

        if (isset($oDados->terminal) && !empty($oDados->terminal)) {
            $operacoesrealizadastef->setTerminal($oDados->terminal);
        }

        if (isset($oDados->confirmadoautorizadora) && !empty($oDados->confirmadoautorizadora)) {
            $operacoesrealizadastef->setConfirmadoautorizadora($oDados->confirmadoautorizadora);
        }

        $operacoesrealizadastef->save();

        return $operacoesrealizadastef->getSequencial();
    }

    public function confirmarOperacao($oDados)
    {
        $operacoesrealizadastefRepository = new OperacoesrealizadastefRepository();

        $operacoesrealizadastef = $operacoesrealizadastefRepository->getBySequencial($oDados->sequencial);
        $operacoesrealizadastef->setConfirmado(true)->save();

        return $operacoesrealizadastef->getSequencial();
    }

    public function desfazerOperacao($oDados)
    {
        $operacoesrealizadastefRepository = new OperacoesrealizadastefRepository();

        $operacoesrealizadastef = $operacoesrealizadastefRepository->getBySequencial($oDados->sequencial);
        $operacoesrealizadastef->setDesfeito(true)->save();

        return $operacoesrealizadastef->getSequencial();
    }

    public function operacoesPendentes($oDados)
    {
        $operacoesrealizadastefRepository = new OperacoesrealizadastefRepository();

        return $operacoesrealizadastefRepository->getAllPendentes(
            $oDados->dataInicio,
            $oDados->dataFim,
            $oDados->terminal
        )->toArray();
    }
}
