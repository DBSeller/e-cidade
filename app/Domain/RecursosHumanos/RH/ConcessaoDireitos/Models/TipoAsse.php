<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAsse extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.tipoasse";
    protected $primaryKey = "h12_codigo";
    protected $fillable = [];
}
