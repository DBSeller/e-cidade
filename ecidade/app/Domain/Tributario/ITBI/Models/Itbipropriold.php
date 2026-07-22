<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbipropriold extends Model
{
    protected $table = "itbipropriold";

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param int $guia
     * @return Itbipropriold
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumcgm()
    {
        return $this->numcgm;
    }

    /**
     * @param int $numcgm
     * @return Itbipropriold
     */
    public function setNumcgm($numcgm)
    {
        $this->numcgm = $numcgm;
        return $this;
    }

    /**
     * @return string
     */
    public function getPri()
    {
        return $this->pri;
    }

    /**
     * @param string $pri
     * @return Itbipropriold
     */
    public function setPri($pri)
    {
        $this->pri = $pri;
        return $this;
    }
}
