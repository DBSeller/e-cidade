<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InformacaoComplementar
 * @property $c121_sequencial
 * @property $c121_sigla
 * @property $c121_descricao
 * @property $c121_sql
 * @property $c121_ajuda
 * @property $c121_nomepropriedade
 * @property $c121_valorpadrao
 */
class InformacaoComplementar extends Model
{
    protected $table = 'contabilidade.conplanoinfocomplementar';
    protected $primaryKey = 'c121_sequencial';
    public $timestamps = false;
}
