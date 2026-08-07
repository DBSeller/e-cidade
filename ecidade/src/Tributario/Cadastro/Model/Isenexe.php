<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Isenexe extends Model 
{
    private $codigo;

    private $anousu;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setAnousu($anousu)
    {
        $this->anousu = $anousu;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getAnousu()
    {
        return $this->anousu;
    }
}
