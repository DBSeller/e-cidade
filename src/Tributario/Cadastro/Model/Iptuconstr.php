<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Iptuconstr extends Model 
{
    private $matric;

    private $idcons;

    private $ano;

    private $area;

    private $areap;

    private $dtlan;

    private $codigo;

    private $numero;

    private $compl;

    private $dtdemo;

    private $idaument;

    private $idprinc;

    private $habite;

    private $pavim;

    private $codprotdemo;

    private $obs;

    public function setMatric($matric)
    {
        $this->matric = $matric;
    }

    public function setIdcons($idcons)
    {
        $this->idcons = $idcons;
    }

    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    public function setArea($area)
    {
        $this->area = $area;
    }

    public function setAreap($areap)
    {
        $this->areap = $areap;
    }

    public function setDtlan($dtlan)
    {
        $this->dtlan = $dtlan;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    public function setCompl($compl)
    {
        $this->compl = $compl;
    }

    public function setDtdemo($dtdemo)
    {
        $this->dtdemo = $dtdemo;
    }

    public function setIdaument($idaument)
    {
        $this->idaument = $idaument;
    }

    public function setIdprinc($idprinc)
    {
        $this->idprinc = $idprinc;
    }

    public function setHabite($habite)
    {
        $this->habite = $habite;
    }

    public function setPavim($pavim)
    {
        $this->pavim = $pavim;
    }

    public function setCodprotdemo($codprotdemo)
    {
        $this->codprotdemo = $codprotdemo;
    }

    public function setObs($obs)
    {
        $this->obs = $obs;
    }

    public function getMatric()
    {
        return $this->matric;
    }

    public function getIdcons()
    {
        return $this->idcons;
    }

    public function getAno()
    {
        return $this->ano;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function getAreap()
    {
        return $this->areap;
    }

    public function getDtlan()
    {
        return $this->dtlan;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function getCompl()
    {
        return $this->compl;
    }

    public function getDtdemo()
    {
        return $this->dtdemo;
    }

    public function getIdaument()
    {
        return $this->idaument;
    }

    public function getIdprinc()
    {
        return $this->idprinc;
    }

    public function getHabite()
    {
        return $this->habite;
    }

    public function getPavim()
    {
        return $this->pavim;
    }

    public function getCodprotdemo()
    {
        return $this->codprotdemo;
    }

    public function getObs()
    {
        return $this->obs;
    }
}
