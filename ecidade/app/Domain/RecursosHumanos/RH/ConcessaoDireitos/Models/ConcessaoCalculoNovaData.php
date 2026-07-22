<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class ConcessaoCalculoNovaData extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.concessaocalculonovadata";
    protected $primaryKey = "rh506_sequencial";
    protected $fillable = [
        'rh506_concessaocalculo',
        'rh506_datanova',
    ];
}
