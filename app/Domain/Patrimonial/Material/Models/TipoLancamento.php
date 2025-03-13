<?php

namespace App\Domain\Patrimonial\Material\Models;

use Illuminate\Database\Eloquent\Model;

class TipoLancamento extends Model
{
    protected $table = 'material.matestoquetipo';
    protected $primaryKey = 'm81_codtipo';
    public $timestamps = false;
}
