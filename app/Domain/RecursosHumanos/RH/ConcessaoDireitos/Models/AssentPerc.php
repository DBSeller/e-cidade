<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class AssentPerc extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.assentperc";
    protected $primaryKey = "rh501_sequencial";
    protected $fillable = [
        'rh501_seqasentconf',
        'rh501_ordem',
        'rh501_perc',
        'rh501_unidade',
        'rh501_valor'
    ];
}
