<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

class Itbilogin extends Model
{
    protected $table = "itbilogin";

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param int $guia
     * @return Itbilogin
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    /**
     * @param int $id_usuario
     * @return Itbilogin
     */
    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
        return $this;
    }
}
