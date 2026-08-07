<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2021;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2020\Decreto as DecretoAlias;

/**
 * Class Decreto
 * @package ECidade\Financeiro\Contabilidade\Exportacao\PADRS\v2020\Layout
 */
class Decreto extends DecretoAlias
{
    /**
     * campos do layout na ordem em que será escrito
     * @var string[]
     */
    protected $dePara = [
        "Número da Lei" => "numeroLei",
        "Data da Lei" => "dataLei",
        "Número do Decreto" => "numeroDecreto",
        "Data do Decreto" => "dataDecreto",
        "Valor Crédito Adicional" => "valorCreditoAdicional",
        "Valor Redução de Dotações" => "valorReducaoDotacoes",
        "Tipo do Crédito Adicional" => "tipoCalculoAdicional",
        "Origem do Recurso" => "origemRecurso",
        "Alterações Orçamentárias" => "alteracoesOrcamentarias",
        "Valor Alterações Orçamentárias" => "valorAlteracoeOrcamentarias",
        "Data Reabertura de Crédito Adicional" => "dataReaberturaCreditoAdicional",
        "Valor do Saldo Reaberto" => "valorSaldoReaberto",
    ];


    protected $alteracoesOrcamentarias;
    protected $valorAlteracoeOrcamentarias;
    protected $dataReaberturaCreditoAdicional;
    protected $valorSaldoReaberto;

    /**
     * @return mixed
     */
    public function getAlteracoesOrcamentarias()
    {
        return $this->alteracoesOrcamentarias;
    }

    /**
     * @param mixed $alteracoesOrcamentarias
     * @return Decreto
     */
    public function setAlteracoesOrcamentarias($alteracoesOrcamentarias)
    {
        $this->alteracoesOrcamentarias = $alteracoesOrcamentarias;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataReaberturaCreditoAdicional()
    {
        return $this->dataReaberturaCreditoAdicional;
    }

    /**
     * @param mixed $dataReaberturaCreditoAdicional
     * @return Decreto
     */
    public function setDataReaberturaCreditoAdicional($dataReaberturaCreditoAdicional)
    {
        $this->dataReaberturaCreditoAdicional = $dataReaberturaCreditoAdicional;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValorAlteracoeOrcamentarias()
    {
        return $this->valorAlteracoeOrcamentarias;
    }

    /**
     * @param mixed $valorAlteracoeOrcamentarias
     * @return Decreto
     */
    public function setValorAlteracoeOrcamentarias($valorAlteracoeOrcamentarias)
    {
        $this->valorAlteracoeOrcamentarias = $valorAlteracoeOrcamentarias;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValorSaldoReaberto()
    {
        return $this->valorSaldoReaberto;
    }

    /**
     * @param mixed $valorSaldoReaberto
     * @return Decreto
     */
    public function setValorSaldoReaberto($valorSaldoReaberto)
    {
        $this->valorSaldoReaberto = $valorSaldoReaberto;
        return $this;
    }

    public function toArray()
    {
        return [
            "numeroLei" => $this->getNumeroLei(),
            "dataLei" => $this->getDataLei(),
            "numeroDecreto" => $this->getNumeroDecreto(),
            "dataDecreto" => $this->getDataDecreto(),
            "valorCreditoAdicional" => $this->getValorCreditoAdicional(),
            "valorReducaoDotacoes" => $this->getValorReducaoDotacoes(),
            "tipoCalculoAdicional" => $this->getTipoCreditoAdicional(),
            "origemRecurso" => $this->getOrigemRecurso(),
            "alteracoesOrcamentarias" => $this->getAlteracoesOrcamentarias(),
            "valorAlteracoeOrcamentarias" => $this->getValorAlteracoeOrcamentarias(),
            "dataReaberturaCreditoAdicional" => $this->getDataReaberturaCreditoAdicional(),
            "valorSaldoReaberto" => $this->getValorSaldoReaberto(),
        ];
    }
}
