<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Iptuisen extends Model 
{
    private $codigo;

    private $matric;

    private $tipo;

    private $dtini;

    private $dtfim;

    private $perc;

    private $dtinc;

    private $idusu;

    private $hist;

    private $arealo;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setMatric($matric)
    {
        $this->matric = $matric;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function setDtini($dtini)
    {
        $this->dtini = $dtini;
    }

    public function setDtfim($dtfim)
    {
        $this->dtfim = $dtfim;
    }

    public function setPerc($perc)
    {
        $this->perc = $perc;
    }

    public function setDtinc($dtinc)
    {
        $this->dtinc = $dtinc;
    }

    public function setIdusu($idusu)
    {
        $this->idusu = $idusu;
    }

    public function setHist($hist)
    {
        $this->hist = $hist;
    }

    public function setArealo($arealo)
    {
        $this->arealo = $arealo;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getMatric()
    {
        return $this->matric;
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function getDtini()
    {
        return $this->dtini;
    }

    public function getDtfim()
    {
        return $this->dtfim;
    }

    public function getPerc()
    {
        return $this->perc;
    }

    public function getDtinc()
    {
        return $this->dtinc;
    }

    public function getIdusu()
    {
        return $this->idusu;
    }

    public function getHist()
    {
        return $this->hist;
    }

    public function getArealo()
    {
        return $this->arealo;
    }
}
