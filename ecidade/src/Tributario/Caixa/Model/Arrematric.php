<?php

namespace ECidade\Tributario\Caixa\Model;

use ECidade\Tributario\Library\Model;

final class Arrematric extends Model
{
    private $numpre;

    private $matric;

    private $perc;

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setMatric($matric)
    {
        $this->matric = $matric;
    }

    public function setPerc($perc)
    {
        $this->perc = $perc;
    }  

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getMatric()
    {
        return $this->matric;
    }

    public function getPerc()
    {
        return $this->perc;
    }
}
