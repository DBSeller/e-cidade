<?php

namespace App\Domain\Patrimonial\Patrimonio\Models;

use Illuminate\Database\Eloquent\Model;

class DivisaoDepartamento extends Model
{
    protected $table = 'patrimonio.departdiv';
    protected $primaryKey = 't30_codigo';
    public $timestamps = false;
}
