<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbidadosimovelsetorloc extends Model
{
    protected $table = "itbidadosimovelsetorloc";

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return Itbidadosimovelsetorloc
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getSetorloc()
    {
        return $this->setorloc;
    }

    /**
     * @param int $setorloc
     * @return Itbidadosimovelsetorloc
     */
    public function setSetorloc($setorloc)
    {
        $this->setorloc = $setorloc;
        return $this;
    }

    /**
     * @return int
     */
    public function getItbidadosimovel()
    {
        return $this->itbidadosimovel;
    }

    /**
     * @param int $itbidadosimovel
     * @return Itbidadosimovelsetorloc
     */
    public function setItbidadosimovel($itbidadosimovel)
    {
        $this->itbidadosimovel = $itbidadosimovel;
        return $this;
    }
}
