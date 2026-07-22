<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcedimentoAvaliacao
 * @package App\Domain\Educacao\Escola\Models
 */
class ProcedimentoAvaliacao extends Model
{
    protected $table = 'escola.procavaliacao';
    protected $primaryKey = 'ed41_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    public function periodoAvaliacao()
    {
        return $this->hasOne(PeriodoAvaliacao::class, 'ed09_i_codigo', 'ed41_i_periodoavaliacao');
    }

    public function formaAvaliacao()
    {
        return $this->hasOne(FormaAvaliacao::class, 'ed37_i_codigo', 'ed41_i_formaavaliacao');
    }
}
