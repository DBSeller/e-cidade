<?php 

namespace ECidade\Tributario\Caixa\Entity;

use ECidade\Tributario\Library\Entity;

final class RegraEmissao extends Entity
{
    private $convenio;

    private $convenioCobranca;

    private $banco;

    private $agencia;

    private $terceiroDigito;

    public function setConvenio($convenio)
    {
        $this->convenio = $convenio;
    }

    public function setConvenioCobranca($convenioCobranca)
    {
        $this->convenioCobranca = $convenioCobranca;
    }

    public function setBanco($banco)
    {
        $this->banco = $banco;
    }

    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
    }

    public function setTerceiroDigito($terceiroDigito)
    {
        $this->terceiroDigito = $terceiroDigito;
    }

    public function getConvenio()
    {
        return $this->convenio;
    }

    public function getConvenioCobranca()
    {
        return $this->convenioCobranca;
    }

    public function getBanco()
    {
        return $this->banco;
    }

    public function getAgencia()
    {
        return $this->agencia;
    }

    public function getTerceiroDigito()
    {
        return $this->terceiroDigito;
    }
}
