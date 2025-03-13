<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Isenproc extends Model 
{
    private $codigo;

    private $codproc;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setCodproc($codproc)
    {
        $this->codproc = $codproc;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getCodproc()
    {
        return $this->codproc;
    }
}
