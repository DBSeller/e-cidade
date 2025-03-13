<?php

namespace ECidade\Tributario\Caixa\Model;

use ECidade\Tributario\Library\Model;

final class Arreinscr extends Model
{
    private $numpre;

    private $inscr;

    private $perc;

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setInscr($inscr)
    {
        $this->inscr = $inscr;
    }

    public function setPerc($perc)
    {
        $this->perc = $perc;
    }  

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getInscr()
    {
        return $this->inscr;
    }

    public function getPerc()
    {
        return $this->perc;
    }
}
