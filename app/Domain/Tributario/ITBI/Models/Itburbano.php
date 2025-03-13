<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itburbano extends Model
{
    protected $table = "itburbano";

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param int $guia
     * @return Itburbano
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
        return $this;
    }

    /**
     * @return float
     */
    public function getFrente()
    {
        return $this->frente;
    }

    /**
     * @param float $frente
     * @return Itburbano
     */
    public function setFrente($frente)
    {
        $this->frente = $frente;
        return $this;
    }

    /**
     * @return float
     */
    public function getFundos()
    {
        return $this->fundos;
    }

    /**
     * @param float $fundos
     * @return Itburbano
     */
    public function setFundos($fundos)
    {
        $this->fundos = $fundos;
        return $this;
    }

    /**
     * @return float
     */
    public function getDireito()
    {
        return $this->direito;
    }

    /**
     * @param float $direito
     * @return Itburbano
     */
    public function setDireito($direito)
    {
        $this->direito = $direito;
        return $this;
    }

    /**
     * @return float
     */
    public function getEsquerdo()
    {
        return $this->esquerdo;
    }

    /**
     * @param float $esquerdo
     * @return Itburbano
     */
    public function setEsquerdo($esquerdo)
    {
        $this->esquerdo = $esquerdo;
        return $this;
    }

    /**
     * @return int
     */
    public function getItbisituacao()
    {
        return $this->itbisituacao;
    }

    /**
     * @param int $itbisituacao
     * @return Itburbano
     */
    public function setItbisituacao($itbisituacao)
    {
        $this->itbisituacao = $itbisituacao;
        return $this;
    }
}
