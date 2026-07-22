<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Representa um problema/condição de saúde do sistema
 * @package App\Domain\Saude\Ambulatorial\Models
 * @property integer $s169_id
 * @property string $s169_descricao
 *
 * @property \Illuminate\Database\Eloquent\Collection $cids
 */
class Problema extends Model
{
    const ASMA = 1;
    const CANCER_COLO_UTERO = 2;
    const CANCER_MAMA = 3;
    const DENGUE = 4;
    const DESNUTRICAO = 5;
    const DIABETES = 6;
    const DPOC = 7;
    const DST = 8;
    const HANSENIASE = 9;
    const HIPERTENSAO_ARTERIAL = 10;
    const OBESIDADE = 11;
    const PRE_NATAL = 12;
    const PUERICULTURA = 13;
    const PUERPERIO = 14;
    const REABILITACAO = 15;
    const RISCO_CARDIOVASCULAR = 16;
    const SAUDE_MENTAL = 17;
    const SAUDE_SEXUAL_REPRODUTIVA = 18;
    const TABAGISMO = 19;
    const TUBURCULOSE = 20;
    const USUARIO_ALCOOL = 21;
    const USUARIO_OUTRAS_DROGAS = 22;

    protected $table = 'ambulatorial.problemas';
    protected $primaryKey = 's169_id';
    public $timestamps = false;

    public function scopePreNatal(Builder $query)
    {
        return $query->where('s169_id', Problema::PRE_NATAL);
    }

    public function cids()
    {
        return $this->belongsToMany(Cid::class, 'ambulatorial.problemacid', 's172_problema', 's172_cid')
            ->using(ProblemaCid::class);
    }
}
