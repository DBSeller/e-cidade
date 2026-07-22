<?php

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Iptucale extends Model
{
    private $anousu;

    private $matric;

    private $idcons;

    private $areaed;

    private $vm2;

    private $pontos;

    private $valor;

    public function setAnousu($anousu)
    {
        $this->anousu = $anousu;
    }

    public function getAnousu()
    {
        return $this->anousu;
    }

    public function setMatric($matric)
    {
        $this->matric = $matric;
    }

    public function getMatric()
    {
        return $this->matric;
    }

    public function setIdcons($idcons)
    {
        $this->idcons = $idcons;
    }

    public function getIdcons()
    {
        return $this->idcons;
    }

    public function setAreaed($areaed)
    {
        $this->areaed = $areaed;
    }

    public function getAreaed()
    {
        return $this->areaed;
    }

    public function setVm2($vm2)
    {
        $this->vm2 = $vm2;
    }

    public function getVm2()
    {
        return $this->vm2;
    }

    public function setPontos($pontos)
    {
        $this->pontos = $pontos;
    }

    public function getPontos()
    {
        return $this->pontos;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getValor()
    {
        return $this->valor;
    }
}
