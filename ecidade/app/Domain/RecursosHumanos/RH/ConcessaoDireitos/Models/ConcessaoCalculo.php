<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class ConcessaoCalculo extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.concessaocalculo";
    protected $primaryKey = "rh504_sequencial";
    protected $fillable = [
        'rh504_regist',
        'rh504_seqassentconf',
        'rh504_seqassentperc',
        'rh504_dtproc',
        'rh504_data'
    ];
}
