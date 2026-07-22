<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Isentaxa extends Model 
{
    private $codigo;

    private $receit;

    private $perc;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setReceit($receit)
    {
        $this->receit = $receit;
    }

    public function setPerc($perc)
    {
        $this->perc = $perc;
    }  

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getReceit()
    {
        return $this->receit;
    }

    public function getPerc()
    {
        return $this->perc;
    }
}
