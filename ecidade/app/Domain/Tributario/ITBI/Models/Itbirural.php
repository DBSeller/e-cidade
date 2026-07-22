<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbirural extends Model
{
    protected $table = "itbirural";

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param $guia
     * @return Itbirural
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
     * @param $frente
     * @return Itbirural
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
     * @param $fundos
     * @return Itbirural
     */
    public function setFundos($fundos)
    {
        $this->fundos = $fundos;
        return $this;
    }

    /**
     * @return float
     */
    public function getProf()
    {
        return $this->prof;
    }

    /**
     * @param $prof
     * @return Itbirural
     */
    public function setProf($prof)
    {
        $this->prof = $prof;
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalimovel()
    {
        return $this->localimovel;
    }

    /**
     * @param $localimovel
     * @return Itbirural
     */
    public function setLocalimovel($localimovel)
    {
        $this->localimovel = $localimovel;
        return $this;
    }

    /**
     * @return float
     */
    public function getDistcidade()
    {
        return $this->distcidade;
    }

    /**
     * @param $distcidade
     * @return Itbirural
     */
    public function setDistcidade($distcidade)
    {
        $this->distcidade = $distcidade;
        return $this;
    }

    /**
     * @return string
     */
    public function getNomelograd()
    {
        return $this->nomelograd;
    }

    /**
     * @param $nomelograd
     * @return Itbirural
     */
    public function setNomelograd($nomelograd)
    {
        $this->nomelograd = $nomelograd;
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
     * @param $area
     * @return Itbirural
     */
    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoordenadas()
    {
        return $this->coordenadas;
    }

    /**
     * @param $coordenadas
     * @return Itbirural
     */
    public function setCoordenadas($coordenadas)
    {
        $this->coordenadas = $coordenadas;
        return $this;
    }
}
