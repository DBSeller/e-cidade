<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Saude\Ambulatorial\Models
 * @property integer $sd27_i_codigo
 * @property integer $sd27_i_rhcbo
 * @property integer $sd27_i_undmed
 * @property boolean $sd27_b_principal
 * @property string $sd27_c_situacao
 *
 * @property ProfissionalUnidade $profissionalUnidade
 */
class EspecialidadeProfissional extends Model
{
    public $timestamps = false;
    protected $table = 'ambulatorial.especmedico';
    protected $primaryKey = 'sd27_i_codigo';

    public function profissionalUnidade()
    {
        return $this->belongsTo(ProfissionalUnidade::class, 'sd27_i_undmed', 'sd04_i_codigo');
    }
}
