<?php

namespace ECidade\Tributario\Caixa\Model;

use ECidade\Tributario\Library\Model;

final class Arrenumcgm extends Model
{
    private $numcgm;

    private $numpre;

    public function setNumcgm($numcgm)
    {
        $this->numcgm = $numcgm;
    }

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function getNumcgm()
    {
        return $this->numcgm;
    }

    public function getNumpre()
    {
        return $this->numpre;
    }
}
