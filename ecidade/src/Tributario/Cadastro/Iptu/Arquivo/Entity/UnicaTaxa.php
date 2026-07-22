<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class UnicaTaxa extends Entity
{
    const CODARRETAXA = 'CODARRETAXA';
    const VLRCALCTAXA = 'VLRCALCTAXA';
    const VLRDESCUNICATAXA1 = 'VLRDESCUNICATAXA1';
    const ALIQDESCUNICATAXA1 = 'ALIQDESCUNICATAXA1';
    const VLRUNICATAXA1 = 'VLRUNICATAXA1';
    const VLRDESCUNICATAXA2 = 'VLRDESCUNICATAXA2';
    const ALIQDESCUNICATAXA2 = 'ALIQDESCUNICATAXA2';
    const VLRUNICATAXA2 = 'VLRUNICATAXA2';
    const VLRDESCUNICATAXA3 = 'VLRDESCUNICATAXA3';
    const ALIQDESCUNICATAXA3 = 'ALIQDESCUNICATAXA3';
    const VLRUNICATAXA3 = 'VLRUNICATAXA3';

    private $codigoArrecadacaoTaxa;

    private $valorCalculo;

    private $valorDescontoUnicaTaxa1;

    private $aliquotaDescontoUnicaTaxa1;

    private $valorUnicaTaxa1;

    private $valorDescontoUnicaTaxa2;

    private $aliquotaDescontoUnicaTaxa2;

    private $valorUnicaTaxa2;

    private $valorDescontoUnicaTaxa3;

    private $aliquotaDescontoUnicaTaxa3;

    private $valorUnicaTaxa3;

    public function setCodigoArrecadacaoTaxa($codigoArrecadacaoTaxa)
    {
        $this->codigoArrecadacaoTaxa = $codigoArrecadacaoTaxa;
    }

    public function setValorCalculo($valorCalculo)
    {
        $this->valorCalculo = $valorCalculo;
    }

    public function setValorDescontoUnicaTaxa1($valorDescontoUnicaTaxa1)
    {
        $this->valorDescontoUnicaTaxa1 = $valorDescontoUnicaTaxa1;
    }

    public function setAliquotaDescontoUnicaTaxa1($aliquotaDescontoUnicaTaxa1)
    {
        $this->aliquotaDescontoUnicaTaxa1 = $aliquotaDescontoUnicaTaxa1;
    }

    public function setValorUnicaTaxa1($valorUnicaTaxa1)
    {
        $this->valorUnicaTaxa1 = $valorUnicaTaxa1;
    }

    public function setValorDescontoUnicaTaxa2($valorDescontoUnicaTaxa2)
    {
        $this->valorDescontoUnicaTaxa2 = $valorDescontoUnicaTaxa2;
    }

    public function setAliquotaDescontoUnicaTaxa2($aliquotaDescontoUnicaTaxa2)
    {
        $this->aliquotaDescontoUnicaTaxa2 = $aliquotaDescontoUnicaTaxa2;
    }

    public function setValorUnicaTaxa2($valorUnicaTaxa2)
    {
        $this->valorUnicaTaxa2 = $valorUnicaTaxa2;
    }

    public function setValorDescontoUnicaTaxa3($valorDescontoUnicaTaxa3)
    {
        $this->valorDescontoUnicaTaxa3 = $valorDescontoUnicaTaxa3;
    }

    public function setAliquotaDescontoUnicaTaxa3($aliquotaDescontoUnicaTaxa3)
    {
        $this->aliquotaDescontoUnicaTaxa3 = $aliquotaDescontoUnicaTaxa3;
    }

    public function setValorUnicaTaxa3($valorUnicaTaxa3)
    {
        $this->valorUnicaTaxa3 = $valorUnicaTaxa3;
    }

    public function getCodigoArrecadacaoTaxa()
    {
        return $this->codigoArrecadacaoTaxa;
    }

    public function getValorCalculo()
    {
        return $this->valorCalculo;
    }

    public function getValorDescontoUnicaTaxa1()
    {
        return $this->valorDescontoUnicaTaxa1;
    }

    public function getAliquotaDescontoUnicaTaxa1()
    {
        return $this->aliquotaDescontoUnicaTaxa1;
    }

    public function getValorUnicaTaxa1()
    {
        return $this->valorUnicaTaxa1;
    }

    public function getValorDescontoUnicaTaxa2()
    {
        return $this->valorDescontoUnicaTaxa2;
    }

    public function getAliquotaDescontoUnicaTaxa2()
    {
        return $this->aliquotaDescontoUnicaTaxa2;
    }

    public function getValorUnicaTaxa2()
    {
        return $this->valorUnicaTaxa2;
    }

    public function getValorDescontoUnicaTaxa3()
    {
        return $this->valorDescontoUnicaTaxa3;
    }

    public function getAliquotaDescontoUnicaTaxa3()
    {
        return $this->aliquotaDescontoUnicaTaxa3;
    }

    public function getValorUnicaTaxa3()
    {
        return $this->valorUnicaTaxa3;
    }
}
