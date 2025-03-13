<?php

namespace App\Domain\Financeiro\Tesouraria\Models;

use Illuminate\Database\Eloquent\Model;

class TipoParcelamento extends Model
{
    protected $table = 'caixa.arretipo';
    protected $primaryKey = 'k00_tipo';
    public $timestamps = false;
}
