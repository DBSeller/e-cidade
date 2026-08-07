<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use App\Domain\RecursosHumanos\Pessoal\Model\Servidor\Cbo;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Saude\Ambulatorial\Models
 * @property integer $s104_i_codigo
 * @property integer $s104_i_prontuario
 * @property integer $s104_i_profissional
 * @property integer $s104_rhcbo
 *
 * @property EspecialidadeProfissional $especialidade
 * @property Cbo $cbo
 */
class ProfissionalAtendimento extends Model
{
    public $timestamps = false;
    protected $table = 'ambulatorial.prontprofatend';
    protected $primaryKey = 's104_i_codigo';

    public function especialidade()
    {
        return $this->belongsTo(EspecialidadeProfissional::class, 's104_i_profissional', 'sd27_i_codigo');
    }

    public function cbo()
    {
        return $this->belongsTo(Cbo::class, 's104_rhcbo', 'rh70_sequencial');
    }
}
