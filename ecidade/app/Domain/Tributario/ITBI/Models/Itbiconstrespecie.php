<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbiconstrespecie extends Model
{
    protected $table = "itbiconstrespecie";

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Itbiconstrespecie
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCaract()
    {
        return $this->caract;
    }

    /**
     * @param int $caract
     * @return Itbiconstrespecie
     */
    public function setCaract($caract)
    {
        $this->caract = $caract;
        return $this;
    }
}
