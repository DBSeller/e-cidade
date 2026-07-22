<?php

namespace ECidade\Tributario\Issqn\Model;

use ECidade\Tributario\Library\Model;

final class Cadvenc extends Model
{
    private $codigo;

    private $parc;

    private $venc;

    private $desc;

    private $perc;

    private $hist;

    private $calculaparcvenc;

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setParc($parc)
    {
        $this->parc = $parc;
    }

    public function setVenc($venc)
    {
        $this->venc = $venc;
    }

    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    public function setPerc($perc)
    {
        $this->perc = $perc;
    }

    public function setHist($hist)
    {
        $this->hist = $hist;
    }

    public function setCalculaparcvenc($calculaparcvenc)
    {
        $this->calculaparcvenc = $calculaparcvenc;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getParc()
    {
        return $this->parc;
    }

    public function getVenc()
    {
        return $this->venc;
    }

    public function getDesc()
    {
        return $this->desc;
    }

    public function getPerc()
    {
        return $this->perc;
    }

    public function getHist()
    {
        return $this->hist;
    }

    public function getCalculaparcvenc()
    {
        return $this->calculaparcvenc;
    }
}
