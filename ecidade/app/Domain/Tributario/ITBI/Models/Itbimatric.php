<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbimatric extends Model
{
    protected $table = "itbimatric";

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param int $guia
     * @return Itbimatric
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
        return $this;
    }

    /**
     * @return int
     */
    public function getMatric()
    {
        return $this->matric;
    }

    /**
     * @param int $matric
     * @return Itbimatric
     */
    public function setMatric($matric)
    {
        $this->matric = $matric;
        return $this;
    }
}
