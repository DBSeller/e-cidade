<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class ConcessaoCalculoLog extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.concessaocalculolog";
    protected $primaryKey = "rh507_sequencial";
    protected $fillable = [
        'rh507_concessaocalculo',
        'rh507_assent'
    ];
}
