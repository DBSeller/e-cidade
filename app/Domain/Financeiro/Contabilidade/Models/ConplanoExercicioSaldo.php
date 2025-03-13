<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $c68_anousu
 * @property $c68_reduz
 * @property $c68_mes
 * @property $c68_debito
 * @property $c68_credito
 */
class ConplanoExercicioSaldo extends Model
{
    protected $table = 'contabilidade.conplanoexesaldo';
    public $timestamps = false;
}
