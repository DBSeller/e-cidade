<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2022;

/**
 * Class Decreto
 * @package ECidade\Financeiro\Contabilidade\Exportacao\PADRS\v2020\Layout
 */
class Decreto extends \ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2021\Decreto
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
        "Recurso Vinculado - Suplementacao e Demais Origem" => "recursoSuplementacao",
        "Recurso Vinculado - Reducoes" => "recursoReducoes",
        "Fonte Recurso Siconfi - Suplementacao e Demais Origem" => "recursoSuplementacaoSiconfi",
        "Fonte Recurso Siconfi - Reducoes" => "recursoReducoesSiconfi",
    ];
    protected $recursoSuplementacao = '0000';
    protected $recursoReducoes = '0000';
    protected $recursoSuplementacaoSiconfi = '0000';
    protected $recursoReducoesSiconfi = '0000';

    /**
     * @return string
     */
    public function getRecursoSuplementacao()
    {
        return $this->recursoSuplementacao;
    }

    /**
     * @param string $recursoSuplementacao
     * @return Decreto
     */
    public function setRecursoSuplementacao($recursoSuplementacao)
    {
        $this->recursoSuplementacao = $recursoSuplementacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecursoReducoes()
    {
        return $this->recursoReducoes;
    }

    /**
     * @param string $recursoReducoes
     * @return Decreto
     */
    public function setRecursoReducoes($recursoReducoes)
    {
        $this->recursoReducoes = $recursoReducoes;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecursoSuplementacaoSiconfi()
    {
        return $this->recursoSuplementacaoSiconfi;
    }

    /**
     * @param string $recursoSuplementacaoSiconfi
     * @return Decreto
     */
    public function setRecursoSuplementacaoSiconfi($recursoSuplementacaoSiconfi)
    {
        $this->recursoSuplementacaoSiconfi = $recursoSuplementacaoSiconfi;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecursoReducoesSiconfi()
    {
        return $this->recursoReducoesSiconfi;
    }

    /**
     * @param string $recursoReducoesSiconfi
     * @return Decreto
     */
    public function setRecursoReducoesSiconfi($recursoReducoesSiconfi)
    {
        $this->recursoReducoesSiconfi = $recursoReducoesSiconfi;
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
            "recursoSuplementacao" => $this->getRecursoSuplementacao(),
            "recursoReducoes" => $this->getRecursoReducoes(),
            "recursoSuplementacaoSiconfi" => $this->getRecursoSuplementacaoSiconfi(),
            "recursoReducoesSiconfi" => $this->getRecursoReducoesSiconfi(),
        ];
    }
}
