<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

class ProfissionalFormacaoSuperior extends Model
{
    protected $table = 'escola.rhformacaosuperior';
    protected $primaryKey = 'ed183_id';
    public $timestamps = false;

    protected $fillable = [
        "ed183_id",
        "ed183_cgm",
        "ed183_nomecurso",
        "ed183_tipoformacao",
        "ed183_areaformacao",
        "ed183_anoconclusao"
    ];
}
