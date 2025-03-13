<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Representa o vinculo de um prontuario e um problema de um paciente
 * @package App\Domain\Saude\Ambulatorial\Models
 * @property integer $s171_id
 * @property integer $s171_prontuario
 * @property integer $s171_problemapaciente
 *
 * @property Prontuario $prontuario
 * @property ProblemaPaciente $problemaPaciente
 */
class ProntuarioProblemaPaciente extends Pivot
{
    protected $table = 'ambulatorial.prontuario_problemaspaciente';
    protected $primaryKey = 's171_id';
    public $timestamps = false;

    public function prontuario()
    {
        return $this->belongsTo(Prontuario::class, 's171_prontuario', 'sd24_i_codigo');
    }

    public function problemaPaciente()
    {
        return $this->belongsTo(ProblemaPaciente::class, 's171_problemapaciente', 's170_id');
    }
}
