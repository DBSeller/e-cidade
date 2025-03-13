<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbinumpre extends Model
{
    protected $table = "itbinumpre";
    public $timestamps = false;

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param int $guia
     * @return Itbinumpre
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumpre()
    {
        return $this->numpre;
    }

    /**
     * @param int $numpre
     * @return Itbinumpre
     */
    public function setNumpre($numpre)
    {
        $this->numpre = $numpre;
        return $this;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Itbinumpre
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return string
     */
    public function getUltimaguia()
    {
        return $this->ultimaguia;
    }

    /**
     * @param string $ultimaguia
     * @return Itbinumpre
     */
    public function setUltimaguia($ultimaguia)
    {
        $this->ultimaguia = $ultimaguia;
        return $this;
    }
}
