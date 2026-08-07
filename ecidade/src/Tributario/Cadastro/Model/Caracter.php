<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Caracter extends Model 
{
    private $codigo;

    private $descr;

    private $grupo;

    private $pontos;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
    }

    public function setPontos($pontos)
    {
        $this->pontos = $pontos;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescr()
    {
        return $this->descr;
    }

    public function getGrupo()
    {
        return $this->grupo;
    }

    public function getPontos()
    {
        return $this->pontos;
    }
}
