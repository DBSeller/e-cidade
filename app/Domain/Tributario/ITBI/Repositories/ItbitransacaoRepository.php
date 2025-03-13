<?php

namespace App\Domain\Tributario\ITBI\Repositories;

use App\Domain\Tributario\ITBI\Models\Itbitransacao;

class ItbitransacaoRepository
{
    private $itibitransacao;

    public function __construct()
    {
        $this->itibitransacao = new Itbitransacao();
    }

    public function get()
    {
        return $this->itibitransacao->orderBy("it04_codigo")->get();
    }

    public function getFormaPagamentoByTipoTransacao($tipoTransacao, $campos = ["*"], $ativa = false)
    {
        $oQuery = $this->itibitransacao->join(
            "itbitransacaoformapag",
            "it25_itbitransacao",
            "=",
            "it04_codigo"
        )->join(
            "itbiformapagamento",
            "it27_sequencial",
            "=",
            "it25_itbiformapagamento"
        )->join(
            "itbitipoformapag",
            "it28_sequencial",
            "=",
            "it27_itbitipoformapag"
        )->where(
            "it04_codigo",
            "=",
            $tipoTransacao
        );

        if ($ativa) {
            $oQuery->where(
                "it25_ativo",
                "=",
                "t"
            );
        }

        $oQuery->orderBy("it25_sequencial")->orderBy("it28_avista");

        return $oQuery->get($campos);
    }
}
