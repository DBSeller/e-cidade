<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Iptutaxacalv extends Model 
{
    private $codigo;

    private $iptutaxanump;

    private $codhis;

    private $receit;

    private $valor;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setIptutaxanump($iptutaxanump)
    {
        $this->iptutaxanump = $iptutaxanump;
    }

    public function setCodhis($codhis)
    {
        $this->codhis = $codhis;
    }

    public function setReceit($receit)
    {
        $this->receit = $receit;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getIptutaxanump()
    {
        return $this->iptutaxanump;
    }

    public function getCodhis()
    {
        return $this->codhis;
    }

    public function getReceit()
    {
        return $this->receit;
    }

    public function getValor()
    {
        return $this->valor;
    }
}
