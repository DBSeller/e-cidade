<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $c65_sequencial
 * @property $c65_descricao
 * @property $c65_sigla
 */
class SistemaConta extends Model
{
    protected $table = 'contabilidade.consistemaconta';
    protected $primaryKey = 'c65_sequencial';
    public $timestamps = false;
}
