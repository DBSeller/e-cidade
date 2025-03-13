<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TurnoReferente
 * @package App\Domain\Educacao\Escola\Models
 * @property integer $ed231_i_codigo
 * @property Turno $ed231_i_turno
 * @property integer $ed231_i_referencia
 */
class TurnoReferente extends Model
{
    protected $table = 'escola.turnoreferente';
    protected $primaryKey = 'ed231_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    protected $appends = ['turno'];
//    protected $hidden = ['ed231_i_turno];

    public function getTurnoAttribute()
    {
        return Turno::find($this->attributes['ed231_i_turno']);
    }
}
