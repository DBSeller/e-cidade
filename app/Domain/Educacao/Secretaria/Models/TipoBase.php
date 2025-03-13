<?php

namespace App\Domain\Educacao\Secretaria\Models;

use ECidade\Enum\Educacao\Secretaria\TipoItinerarioFormativoEnum;
use Illuminate\Database\Eloquent\Model;

class TipoBase extends Model
{
    protected $table = "secretariadeeducacao.tipobase";
    protected $primaryKey = "ed182_id";

    protected $fillable = [
        'ed182_id',
        'ed182_descricao',
        'ed182_estrutura_curricular',
        'ed182_tipo_itinerario_informativo',
        'ed182_compos_itinerario_integrado',
        'ed182_tipo_curso_itinerario_tec_prof',
        'ed182_itinerario_concomitante',
        'ed182_ativo'
    ];

    public $timestamps = false;
}
