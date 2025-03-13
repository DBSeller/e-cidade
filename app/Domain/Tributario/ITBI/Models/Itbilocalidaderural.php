<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbilocalidaderural extends Model
{
    protected $table = "itbilocalidaderural";

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Itbilocalidaderural
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param int $guia
     * @return Itbilocalidaderural
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
        return $this;
    }

    /**
     * @return int
     */
    public function getLocalidaderural()
    {
        return $this->localidaderural;
    }

    /**
     * @param int $localidaderural
     * @return Itbilocalidaderural
     */
    public function setLocalidaderural($localidaderural)
    {
        $this->localidaderural = $localidaderural;
        return $this;
    }
}
