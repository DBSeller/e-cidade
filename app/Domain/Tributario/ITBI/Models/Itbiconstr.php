<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbiconstr extends Model
{
    protected $table = "itbiconstr";

    /**
     * @param $codigo
     * @return Itbiconstr
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param $guia
     * @return Itbiconstr
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
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
     * @param $area
     * @return Itbiconstr
     */
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }

    /**
     * @return float
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * @param $areatrans
     * @return Itbiconstr
     */
    public function setAreatrans($areatrans)
    {
        $this->areatrans = $areatrans;
        return $this;
    }

    /**
     * @return float
     */
    public function getAreatrans()
    {
        return $this->areatrans;
    }

    /**
     * @param $ano
     * @return Itbiconstr
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param $obs
     * @return Itbiconstr
     */
    public function setObs($obs)
    {
        $this->obs = $obs;
        return $this;
    }

    /**
     * @return string
     */
    public function getObs()
    {
        return $this->obs;
    }

    /**
     * @param $coordenadas
     * @return Itbiconstr
     */
    public function setCoordenadas($coordenadas)
    {
        $this->coordenadas = $coordenadas;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoordenadas()
    {
        return $this->coordenadas;
    }
}
