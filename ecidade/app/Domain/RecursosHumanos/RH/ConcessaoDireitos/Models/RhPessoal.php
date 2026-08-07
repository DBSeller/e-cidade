<?php

namespace App\Domain\RecursosHumanos\RH\ConcessaoDireitos\Models;

use Illuminate\Database\Eloquent\Model;

class RhPessoal extends Model
{
    public $timestamps = false;
    protected $table    = "rhpessoal";
    protected $primaryKey = "rh01_regist";
    protected $fillable = [];
}
