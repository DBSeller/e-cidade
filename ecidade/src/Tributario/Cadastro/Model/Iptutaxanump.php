<?php 

namespace ECidade\Tributario\Cadastro\Model;

use ECidade\Tributario\Library\Model;

final class Iptutaxanump extends Model 
{
    private $codigo;

    private $matric;

    private $numpre;

    private $iptucadtaxaexe;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setMatric($matric)
    {
        $this->matric = $matric;
    }

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setIptucadtaxaexe($iptucadtaxaexe)
    {
        $this->iptucadtaxaexe = $iptucadtaxaexe;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getMatric()
    {
        return $this->matric;
    }

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getIptucadtaxaexe()
    {
        return $this->iptucadtaxaexe;
    }
}
