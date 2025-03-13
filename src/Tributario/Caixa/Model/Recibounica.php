<?php

namespace ECidade\Tributario\Caixa\Model;

use \DateTime;
use ECidade\Tributario\Library\Model;

final class Recibounica extends Model
{
    private $numpre;

    private $dtvenc;

    private $dtoper;

    private $percdes;

    private $tipoger;

    private $recibounicageracao;

    private $sequencial;

    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
    }

    public function setDtvenc(DateTime $dtvenc)
    {
        $this->dtvenc = $dtvenc;
    }

    public function setDtoper(DateTime $dtoper)
    {
        $this->dtoper = $dtoper;
    }

    public function setPercdes($percdes)
    {
        $this->percdes = $percdes;
    }

    public function setTipoger($tipoger)
    {
        $this->tipoger = $tipoger;
    }

    public function setRecibounicageracao($recibounicageracao)
    {
        $this->recibounicageracao = $recibounicageracao;
    }

    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }
    
    public function getNumpre()
    {
        return $this->numpre;
    }

    public function getDtvenc()
    {
        return $this->dtvenc;
    }

    public function getDtoper()
    {
        return $this->dtoper;
    }

    public function getPercdes()
    {
        return $this->percdes;
    }

    public function getTipoger()
    {
        return $this->tipoger;
    }

    public function getRecibounicageracao()
    {
        return $this->recibounicageracao;
    }

    public function getSequencial()
    {
        return $this->sequencial;
    }
}
