<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class ConcessaoCalculoNovaDataLog extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.concessaocalculonovadatalog";
    protected $primaryKey = "rh508_sequencial";
    protected $fillable = [
        'rh508_concessaocalculo',
        'rh508_codigo'
    ];
}
