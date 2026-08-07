<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FormaAvaliacao
 * @package App\Domain\Educacao\Escola\Models
 * @property integer $ed37_i_codigo
 * @property string $ed37_c_descr
 * @property string $ed37_c_tipo
 * @property integer $ed37_i_menorvalor
 * @property integer $ed37_i_maiorvalor
 * @property integer $ed37_i_variacao
 * @property string $ed37_c_minimoaprov
 * @property string $ed37_c_parecerarmaz
 * @property integer $ed37_i_escola
 */
class FormaAvaliacao extends Model
{
    protected $table = 'escola.formaavaliacao';
    protected $primaryKey = 'ed37_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    public function getEd37CTipoAttribute()
    {
        return trim($this->attributes['ed37_c_tipo']);
    }
}
