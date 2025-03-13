<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Bairro
 * @package App\Domain\Educacao\Escola\Models
 * @property integer $j13_codi
 * @property string $j13_descr
 * @property string $j13_codant
 * @property boolean $j13_rural
 */
class Bairro extends Model
{
    protected $table = "cadastro.bairro";
    protected $primaryKey = 'j13_codi';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->j13_codi;
    }

    /**
     * @param int $j13_codi
     */
    public function setCodigo($j13_codi)
    {
        $this->j13_codi = $j13_codi;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->j13_descr;
    }

    /**
     * @param string $j13_descr
     */
    public function setDescricao($j13_descr)
    {
        $this->j13_descr = $j13_descr;
    }

    /**
     * @return string
     */
    public function getCodant()
    {
        return $this->j13_codant;
    }

    /**
     * @param string $j13_codant
     */
    public function setCodant($j13_codant)
    {
        $this->j13_codant = $j13_codant;
    }

    /**
     * @return bool
     */
    public function isRural()
    {
        return $this->j13_rural;
    }

    /**
     * @param bool $j13_rural
     */
    public function setRural($j13_rural)
    {
        $this->j13_rural = $j13_rural;
    }
}
