<?php

namespace App\Domain\Financeiro\Tesouraria\Models;

use App\Domain\Financeiro\Tesouraria\Models\TipoParcelamento;

use Illuminate\Database\Eloquent\Model;

class DescrTipoParcelamento extends Model
{
    protected $table = 'caixa.cadtipoparcdeb';
    protected $primaryKey = 'k41_cadtipoparc';
    public $timestamps = false;
}
