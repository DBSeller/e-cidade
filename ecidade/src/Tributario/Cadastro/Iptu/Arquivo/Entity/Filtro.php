<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

final class Filtro
{
    private $quantidade;

    private $ano;
    
    private $iptu;

    private $taxas;

    private $matriculas;

    private $cotaUnicas;

    private $terceiroDigitoUnica;

    private $terceiroDigitoParcela;

    private $entregaValido;

    private $cidadeBranco;

    private $quantidadeParcela;
    
    public function __construct($quantidade, $ano)
    {
        $this->quantidade = $quantidade;
        $this->ano = $ano;
        $this->iptu = false;
        $this->taxas = array();
        $this->matriculas = array();
        $this->cotaUnicas = array();
        $this->terceiroDigitoUnica = null;
        $this->terceiroDigitoParcela = null;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    public function setIptu($iptu)
    {
        $this->iptu = $iptu;
    }

    public function setTaxas($taxas)
    {
        $this->taxas = $taxas;
    }

    public function setMatriculas($matriculas)
    {
        $this->matriculas = $matriculas;
    }

    public function setCotaUnicas(array $cotaUnicas)
    {
        $this->cotaUnicas = $cotaUnicas;
    }

    public function setTerceiroDigitoUnica($terceiroDigitoUnica)
    {
        if ($terceiroDigitoUnica == 'seis') {
            $terceiroDigitoUnica = 6;
        } else if ($terceiroDigitoUnica == 'sete') {
            $terceiroDigitoUnica = 7;
        }

        $this->terceiroDigitoUnica = $terceiroDigitoUnica;
    }

    public function setTerceiroDigitoParcela($terceiroDigitoParcela)
    {
        if ($terceiroDigitoParcela == 'seis') {
            $terceiroDigitoParcela = 6;
        } else if ($terceiroDigitoParcela == 'sete') {
            $terceiroDigitoParcela = 7;
        }

        $this->terceiroDigitoParcela = $terceiroDigitoParcela;
    }

    public function setEntregaValido($entregaValido)
    {
        $this->entregaValido = $entregaValido;
    }

    public function setCidadeBranco($cidadeBranco)
    {
        $this->cidadeBranco = $cidadeBranco;
    }

    public function setQuantidadeParcela($quantidadeParcela)
    {
        $this->quantidadeParcela = $quantidadeParcela;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function getAno()
    {
        return $this->ano;
    }

    public function getMatriculas()
    {
        return $this->matriculas;
    }

    public function getTaxas()
    {
        return $this->taxas;
    }

    public function getCotaUnicas()
    {
        return $this->cotaUnicas;
    }

    public function hasIptu()
    {
        return $this->iptu;
    }

    public function hasTaxas()
    {
        return !empty($this->taxas);
    }

    public function hasCotaUnicas()
    {
        return !empty($this->cotaUnicas);
    }

    public function getTerceiroDigitoUnica()
    {
        return $this->terceiroDigitoUnica;
    }

    public function getTerceiroDigitoParcela()
    {
        return $this->terceiroDigitoParcela;
    }

    public function getEntregaValido()
    {
        return $this->entregaValido;
    }

    public function getCidadeBranco()
    {
        return $this->cidadeBranco;
    }

    public function getQuantidadeParcela()
    {
        return $this->quantidadeParcela;
    }
}
