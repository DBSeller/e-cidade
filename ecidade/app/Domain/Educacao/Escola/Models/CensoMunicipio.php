<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CensoMunicipio
 * @package App\Domain\Educacao\Escola\Models
 * @property integer $ed261_i_codigo
 * @property integer $ed261_i_censouf
 * @property string $ed261_c_nome
 */
class CensoMunicipio extends Model
{
    protected $table = 'escola.censomunic';
    protected $primaryKey = 'ed261_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    protected $casts = [
        'ed261_i_codigo' => 'integer',
        'ed261_i_censouf' => 'integer',
        'ed261_c_nome' => 'string',
    ];

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->ed261_i_codigo;
    }

    /**
     * @return integer
     */
    public function getCensoUf()
    {
        return $this->ed261_i_censouf;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->ed261_c_nome;
    }
}
