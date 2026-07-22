<?php

namespace App\Domain\Patrimonial\Material\Models;

use Illuminate\Database\Eloquent\Model;

class ValorMovimentacaoEstoque extends Model
{
    protected $table = 'material.matestoqueinimeipm';
    protected $primaryKey = 'm89_codigo';
    public $timestamps = false;
}
