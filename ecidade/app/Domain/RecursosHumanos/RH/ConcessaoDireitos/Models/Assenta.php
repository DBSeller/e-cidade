<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class Assenta extends Model
{
    public $timestamps = false;
    protected $table    = "recursoshumanos.assenta";
    protected $primaryKey = "h16_codigo";
    protected $fillable = [];
}
