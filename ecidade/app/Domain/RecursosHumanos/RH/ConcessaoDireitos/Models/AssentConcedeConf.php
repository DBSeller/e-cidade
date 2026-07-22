<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class AssentConcedeConf extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.assentconcedeconf";
    protected $primaryKey = "rh503_sequencial";
    protected $fillable = [
        'rh503_seqassentconf',
        'rh503_acao',
        'rh503_tipo',
        'rh503_condicao',
        'rh503_codigo',
        'rh503_formula'
    ];
}
