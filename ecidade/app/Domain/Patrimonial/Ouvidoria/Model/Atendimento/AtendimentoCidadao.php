<?php

namespace App\Domain\Patrimonial\Ouvidoria\Model\Atendimento;

use Illuminate\Database\Eloquent\Model;

class AtendimentoCidadao extends Model
{
    protected $table = 'ouvidoria.ouvidoriaatendimentocidadao';
    protected $primaryKey = 'ov10_sequencial';
    public $timestamps = false;

    public function atendimento()
    {
        return $this->belongsTo(Atendimento::class, "ov10_ouvidoriaatendimento");
    }

    public function cidadao()
    {
        return $this->belongsTo(\App\Domain\Patrimonial\Ouvidoria\Model\Cidadao\Cidadao::class, "ov10_cidadao");
    }
}
