<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class AssentForm extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.assentform";
    protected $primaryKey = "rh502_sequencial";
    protected $fillable = [
        'rh502_codigo',
        'rh502_condicao',
        'rh502_resultado',
        'rh502_seqassentconf',
        'rh502_operador',
        'rh502_multiplicador'
    ];
}
