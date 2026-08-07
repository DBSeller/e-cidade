<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Procedimento
 * @package App\Domain\Educacao\Escola\Models
 * @property integer $ed40_i_codigo
 * @property integer $ed40_i_formaavaliacao
 * @property string $ed40_c_descr
 * @property integer $ed40_i_percfreq
 * @property string $ed40_c_contrfreqmpd
 * @property integer $ed40_i_calcfreq
 * @property boolean $ed40_desativado
 */
class Procedimento extends Model
{
    protected $table = 'escola.procedimento';
    protected $primaryKey = 'ed40_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    public function procedimentosAvaliacao()
    {
        return $this->hasMany(ProcedimentoAvaliacao::class, 'ed41_i_procedimento', 'ed40_i_codigo')
            ->with('periodoAvaliacao')
            ->with('formaAvaliacao')
            ->orderBy('ed41_i_sequencia');
    }
}
