<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbinomecgm extends Model
{
    protected $table = "itbinomecgm";

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Itbinomecgm
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getItbinome()
    {
        return $this->itbinome;
    }

    /**
     * @param int $itbinome
     * @return Itbinomecgm
     */
    public function setItbinome($itbinome)
    {
        $this->itbinome = $itbinome;
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
     * @return Itbinomecgm
     */
    public function setNumcgm($numcgm)
    {
        $this->numcgm = $numcgm;
        return $this;
    }
}
