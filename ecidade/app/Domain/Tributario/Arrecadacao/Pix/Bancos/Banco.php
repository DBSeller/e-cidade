<?php

namespace App\Domain\Tributario\Arrecadacao\Pix\Bancos;

use \App\Domain\Patrimonial\Protocolo\Model\Cgm;

use convenio;

abstract class Banco
{
    protected $convenio;

    protected $cgm;

    protected $valor;

    protected $vencimento;

    protected $codigoArrecadacao;

    protected $parcela;

    protected $codigoBarras;

    public function getConvenio()
    {
        return $this->convenio;
    }

    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;
    }

    public function getCgm()
    {
        return $this->cgm;
    }

    public function setCgm(Cgm $cgm)
    {
        $this->cgm = $cgm;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getVencimento()
    {
        return $this->vencimento;
    }

    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;
    }

    public function getcodigoArrecadacao()
    {
        return $this->codigoArrecadacao;
    }

    public function setcodigoArrecadacao($codigoArrecadacao)
    {
        $this->codigoArrecadacao = $codigoArrecadacao;
    }

    public function getParcela()
    {
        return $this->parcela;
    }

    public function setParcela($parcela)
    {
        $this->parcela = $parcela;
    }

    /**
     * @return mixed
     */
    public function getCodigoBarras()
    {
        return $this->codigoBarras;
    }

    /**
     * @param mixed $codigoBarras
     * @return Banco
     */
    public function setCodigoBarras($codigoBarras)
    {
        $this->codigoBarras = $codigoBarras;
        return $this;
    }
}
