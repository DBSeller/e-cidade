<?php

namespace App\Domain\Financeiro\Empenho\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAquisicaoProducaoRural extends Model
{
    protected $table = 'empenho.emptipoaquisicaoproducaorural';
    protected $primaryKey = 'e159_sequencial';
    public $timestamps = false;
}
