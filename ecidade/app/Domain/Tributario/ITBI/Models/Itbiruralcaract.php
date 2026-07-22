<?php

namespace App\Domain\Tributario\ITBI\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Domain\Tributario\ITBI\Models\Itbiruralcaract
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Itbiruralcaract joinCaracter()
 * @method static \Illuminate\Database\Eloquent\Builder|Itbiruralcaract guia($guia)
 * @method static \Illuminate\Database\Eloquent\Builder|Itbiruralcaract tipo($tipo)
 */
class Itbiruralcaract extends Model
{
    protected $table = "itbiruralcaract";

    /**
     * @return int
     */
    public function getGuia()
    {
        return $this->guia;
    }

    /**
     * @param int $guia
     * @return Itbiruralcaract
     */
    public function setGuia($guia)
    {
        $this->guia = $guia;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Itbiruralcaract
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     * @return Itbiruralcaract
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipocaract()
    {
        return $this->tipocaract;
    }

    /**
     * @param int $tipocaract
     * @return Itbiruralcaract
     */
    public function setTipocaract($tipocaract)
    {
        $this->tipocaract = $tipocaract;
        return $this;
    }

    public function scopeJoinCaracter($query)
    {
        return $query->join("caracter", "j31_codigo", "=", "it19_codigo");
    }

    public function scopeGuia($query, $guia)
    {
        return $query->where("it19_guia", "=", $guia);
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where("it19_tipocaract", "=", $tipo);
    }
}
