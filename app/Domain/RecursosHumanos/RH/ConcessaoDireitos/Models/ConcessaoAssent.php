<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class ConcessaoAssent extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.concessaoassent";
    protected $primaryKey = "rh505_sequencial";
    protected $fillable = [
        'rh505_concessaocalculo',
        'rh505_codigo',
        'rh505_anousu',
        'rh505_mesusu',
        'rh505_data'
    ];
}
