<?php

namespace ECidade\Tributario\Caixa\Model;

use ECidade\Tributario\Library\Model;

final class Arrehist extends Model
{
    private $numpre;

    private $numpar;

    private $hist;

    private $dtoper;

    private $hora;

    private $id_usuario;

    private $histtxt;

    private $limithist;

    private $idhist;

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setNumpar($numpar)
    {
        $this->numpar = $numpar;
    }

    public function setHist($hist)
    {
        $this->hist = $hist;
    }

    public function setDtoper($dtoper)
    {
        $this->dtoper = $dtoper;
    }

    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    public function setIdusuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function setHisttxt($histtxt)
    {
        $this->histtxt = $histtxt;
    }

    public function setLimithist($limithist)
    {
        $this->limithist = $limithist;
    }

    public function setIdhist($idhist)
    {
        $this->idhist = $idhist;
    }

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getNumpar()
    {
        return $this->numpar;
    }

    public function getHist()
    {
        return $this->hist;
    }

    public function getDtoper()
    {
        return $this->dtoper;
    }

    public function getHora()
    {
        return $this->hora;
    }

    public function getIdusuario()
    {
        return $this->id_usuario;
    }

    public function getHisttxt()
    {
        return $this->histtxt;
    }

    public function getLimithist()
    {
        return $this->limithist;
    }

    public function getIdhist()
    {
        return $this->idhist;
    }
}
