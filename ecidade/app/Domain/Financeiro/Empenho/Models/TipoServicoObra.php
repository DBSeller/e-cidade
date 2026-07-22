<?php

namespace App\Domain\Financeiro\Empenho\Models;

use Illuminate\Database\Eloquent\Model;

class TipoServicoObra extends Model
{
    const TIPO_LABELS = [
        0 => '0 - Não é obra de construção civil ou não está sujeita a matrícula de obra',
        1 => '1 - É obra de construção civil, modalidade empreitada total',
        2 => '2 - É obra de construção civil, modalidade empreitada parcial'
    ];

    protected $table = 'empenho.emptiposervicoobra';
    protected $primaryKey = 'e154_sequencial';
    public $timestamps = false;
}
