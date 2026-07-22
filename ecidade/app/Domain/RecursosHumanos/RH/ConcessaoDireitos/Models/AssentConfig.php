<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class AssentConfig extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.assentconf";
    protected $primaryKey = "rh500_sequencial";
    protected $fillable = [
        'rh500_assentamento',
        'rh500_datalimite',
        'rh500_condede',
        'rh500_naoconcede',
        'rh500_selecao'
    ];
}
