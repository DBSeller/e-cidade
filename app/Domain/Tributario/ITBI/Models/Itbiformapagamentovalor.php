<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbiformapagamentovalor extends Model
{
    protected $table = "itbiformapagamentovalor";

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Itbiformapagamentovalor
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getItbitransacaoformapag()
    {
        return $this->itbitransacaoformapag;
    }

    /**
     * @param int $itbitransacaoformapag
     * @return Itbiformapagamentovalor
     */
    public function setItbitransacaoformapag($itbitransacaoformapag)
    {
        $this->itbitransacaoformapag = $itbitransacaoformapag;
        return $this;
    }

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param int $guia
     * @return Itbiformapagamentovalor
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     * @return Itbiformapagamentovalor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }
}
