<?php

namespace App\Domain\Patrimonial\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $licitacao_id
 */
class LicitacaoTramita extends Model
{
    protected $table = 'licitacao.licitacaotramita';
    public $timestamps = false;
}
