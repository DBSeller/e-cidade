<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PeriodoAvaliacao
 * @package App\Domain\Educacao\Escola\Models
 */
class PeriodoAvaliacao extends Model
{
    protected $table = 'escola.periodoavaliacao';
    protected $primaryKey = 'ed09_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    public function getEd09CDescrAttribute()
    {
        return trim($this->attributes['ed09_c_descr']);
    }
}
