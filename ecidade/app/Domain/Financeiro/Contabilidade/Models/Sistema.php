<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Detalhamento da conta / Identificaчуo da Conta
 * @property $c52_codsis
 * @property $c52_descr
 * @property $c52_descrred
 */
class Sistema extends Model
{
    protected $table = 'contabilidade.consistema';
    protected $primaryKey = 'c52_codsis';
    public $timestamps = false;
}
