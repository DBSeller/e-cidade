<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Iptucadtaxa extends Model 
{
    private $iptucadtaxa;

    private $descr;

    public function setIptucadtaxa($iptucadtaxa)
    {
        $this->iptucadtaxa = $iptucadtaxa;
    }

    public function setDescr($descr)
    {
        $this->descr = $descr;
    }

    public function getIptucadtaxa()
    {
        return $this->iptucadtaxa;
    }

    public function getDescr()
    {
        return $this->descr;
    }
}
