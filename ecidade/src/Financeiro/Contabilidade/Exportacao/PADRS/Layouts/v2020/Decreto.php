<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2020;

use ECidade\Core\Mappers\ParseArray;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\LayoutPad;

class Decreto extends ParseArray implements LayoutPad
{
    protected $dePara = [
        "Número da Lei" => "numeroLei",
        "Data da Lei" => "dataLei",
        "Número do Decreto" => "numeroDecreto",
        "Data do Decreto" => "dataDecreto",
        "Valor Crédito Adicional" => "valorCreditoAdicional",
        "Valor Redução de Dotações" => "valorReducaoDotacoes",
        "Tipo do Crédito Adicional" => "tipoCalculoAdicional",
        "Origem do Recurso" => "origemRecurso",
    ];

    /**
     * @var string
     */
    protected $numeroLei;
    /**
     * @var string
     */
    protected $dataLei;
    /**
     * @var string
     */
    protected $numeroDecreto;
    /**
     * @var string
     */
    protected $dataDecreto;
    /**
     * @var string
     */
    protected $valorCreditoAdicional;
    /**
     * @var string
     */
    protected $valorReducaoDotacoes;
    /**
     * @var string
     */
    protected $tipoCreditoAdicional;
    /**
     * @var string
     */
    protected $origemRecurso;

    /**
     * @return string
     */
    public function getNumeroLei()
    {
        return $this->numeroLei;
    }

    /**
     * @param string $numeroLei
     * @return Decreto
     */
    public function setNumeroLei($numeroLei)
    {
        $this->numeroLei = $numeroLei;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataLei()
    {
        return $this->dataLei;
    }

    /**
     * @param string $dataLei
     * @return Decreto
     */
    public function setDataLei($dataLei)
    {
        $this->dataLei = $dataLei;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumeroDecreto()
    {
        return $this->numeroDecreto;
    }

    /**
     * @param string $numeroDecreto
     * @return Decreto
     */
    public function setNumeroDecreto($numeroDecreto)
    {
        $this->numeroDecreto = $numeroDecreto;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataDecreto()
    {
        return $this->dataDecreto;
    }

    /**
     * @param string $dataDecreto
     * @return Decreto
     */
    public function setDataDecreto($dataDecreto)
    {
        $this->dataDecreto = $dataDecreto;
        return $this;
    }

    /**
     * @return string
     */
    public function getValorCreditoAdicional()
    {
        return $this->valorCreditoAdicional;
    }

    /**
     * @param string $valorCreditoAdicional
     * @return Decreto
     */
    public function setValorCreditoAdicional($valorCreditoAdicional)
    {
        $this->valorCreditoAdicional = $valorCreditoAdicional;
        return $this;
    }

    /**
     * @return string
     */
    public function getValorReducaoDotacoes()
    {
        return $this->valorReducaoDotacoes;
    }

    /**
     * @param string $valorReducaoDotacoes
     * @return Decreto
     */
    public function setValorReducaoDotacoes($valorReducaoDotacoes)
    {
        $this->valorReducaoDotacoes = $valorReducaoDotacoes;
        return $this;
    }

    /**
     * @return string
     */
    public function getTipoCreditoAdicional()
    {
        return $this->tipoCreditoAdicional;
    }

    /**
     * @param string $tipoCreditoAdicional
     * @return Decreto
     */
    public function setTipoCreditoAdicional($tipoCreditoAdicional)
    {
        $this->tipoCreditoAdicional = $tipoCreditoAdicional;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrigemRecurso()
    {
        return $this->origemRecurso;
    }

    /**
     * @param string $origemRecurso
     * @return Decreto
     */
    public function setOrigemRecurso($origemRecurso)
    {
        $this->origemRecurso = $origemRecurso;
        return $this;
    }

    /**
     * @return array
     */
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
        ];
    }
}
