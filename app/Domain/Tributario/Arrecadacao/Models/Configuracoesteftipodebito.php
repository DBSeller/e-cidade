<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracoesteftipodebito extends Model
{
    protected $table = "configuracoesteftipodebito";

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Configuracoesteftipodebito
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param int $tipo
     * @return Configuracoesteftipodebito
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return string
     */
    public function getAceitatef()
    {
        return $this->aceitatef;
    }

    /**
     * @param string $aceitatef
     * @return Configuracoesteftipodebito
     */
    public function setAceitatef($aceitatef)
    {
        $this->aceitatef = $aceitatef;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaximoparcelas()
    {
        return $this->maximoparcelas;
    }

    /**
     * @param int $maximoparcelas
     * @return Configuracoesteftipodebito
     */
    public function setMaximoparcelas($maximoparcelas)
    {
        $this->maximoparcelas = $maximoparcelas;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorminimoparcelafisica()
    {
        return $this->valorminimoparcelafisica;
    }

    /**
     * @param float $valorminimoparcelafisica
     * @return Configuracoesteftipodebito
     */
    public function setValorminimoparcelafisica($valorminimoparcelafisica)
    {
        $this->valorminimoparcelafisica = $valorminimoparcelafisica;
        return $this;
    }

    /**
     * @return float
     */
    public function getValorminimoparcelajuridica()
    {
        return $this->valorminimoparcelajuridica;
    }

    /**
     * @param float $valorminimoparcelajuridica
     * @return Configuracoesteftipodebito
     */
    public function setValorminimoparcelajuridica($valorminimoparcelajuridica)
    {
        $this->valorminimoparcelajuridica = $valorminimoparcelajuridica;
        return $this;
    }
}
