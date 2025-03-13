<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Iptucalh extends Model 
{
    private $codhis;

    private $descr;

    public function setCodhis($codhis)
    {
        $this->codhis = $codhis;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    public function getCodhis()
    {
        return $this->codhis;
    }

    public function getDescr()
    {
        return $this->descr;
    }
}
