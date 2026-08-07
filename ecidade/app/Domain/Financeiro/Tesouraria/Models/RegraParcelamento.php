<?php

namespace App\Domain\Financeiro\Tesouraria\Models;

use Illuminate\Database\Eloquent\Model;

class RegraParcelamento extends Model
{
    protected $table = 'caixa.cadtipoparc';
    protected $primaryKey = 'k40_codigo';
    public $timestamps = false;
}
