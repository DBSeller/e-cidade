<?php

namespace ECidade\Tributario\Caixa\Model;

use \DateTime;
use ECidade\Tributario\Library\Model;

final class Recibocodbar extends Model
{
    private $numpre;

    private $codbar;

    private $linhadigitavel;

    private $nossonumero;

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setCodbar($codbar)
    {
        $this->codbar = $codbar;
    }

    public function setLinhadigitavel($linhadigitavel)
    {
        $this->linhadigitavel = $linhadigitavel;
    }

    public function setNossonumero($nossonumero)
    {
        $this->nossonumero = $nossonumero;
    }

    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getCodbar()
    {
        return $this->codbar;
    }

    public function getLinhadigitavel()
    {
        return $this->linhadigitavel;
    }

    public function getNossonumero()
    {
        return $this->nossonumero;
    }
}
