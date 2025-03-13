<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MatriculaTurnoReferente
 * @package App\Domain\Educacao\Escola\Models
 * @property integer $ed337_codigo
 * @property integer $ed337_matricula
 * @property integer $ed337_turmaturnoreferente
 */
class MatriculaTurnoReferente extends Model
{
    protected $table = 'escola.matriculaturnoreferente';
    protected $primaryKey = 'ed337_codigo';
    public $timestamps = false;
    public $incrementing = false;
}
