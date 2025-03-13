<?php

namespace ECidade\Tributario\Caixa\Model;

use ECidade\Tributario\Library\Model;

final class Arrecad extends Model
{
    private $numpre;

    private $numpar;

    private $numcgm;

    private $dtoper;

    private $receit;

    private $hist;

    private $valor;

    private $dtvenc;

    private $numtot;

    private $numdig;

    private $tipo;

    private $tipojm;

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
    }

    public function getNumpar()
    {
        return $this->numpar;
    }

    public function setNumcgm($numcgm)
    {
        $this->numcgm = $numcgm;
    }

    public function getNumcgm()
    {
        return $this->numcgm;
    }

    public function setDtoper(\DateTime $dtoper)
    {
        $this->dtoper = $dtoper;
    }

    public function getDtoper()
    {
        return $this->dtoper;
    }

    public function setReceit($receit)
    {
        $this->receit = $receit;
    }

    public function getReceit()
    {
        return $this->receit;
    }

    public function setHist($hist)
    {
        $this->hist = $hist;
    }

    public function getHist()
    {
        return $this->hist;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function setDtvenc(\DateTime $dtvenc)
    {
        $this->dtvenc = $dtvenc;
    }

    public function getDtvenc()
    {
        return $this->dtvenc;
    }

    public function setNumtot($numtot)
    {
        $this->numtot = $numtot;
    }

    public function getNumdig()
    {
        return $this->numdig;
    }

    public function setNumdig($numdig)
    {
        $this->numdig = $numdig;
    }

    public function getNumtot()
    {
        return $this->numtot;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipojm($tipojm)
    {
        $this->tipojm = $tipojm;
    }

    public function getTipojm()
    {
        return $this->tipojm;
    }
}
