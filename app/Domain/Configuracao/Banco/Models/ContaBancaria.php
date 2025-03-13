<?php

namespace App\Domain\Configuracao\Banco\Models;

use App\Domain\Financeiro\Contabilidade\Models\ConplanoContaBancaria;
use Illuminate\Database\Eloquent\Model;

/**
 * @class ContaBancaria
 * @property $db83_sequencial
 * @property $db83_descricao
 * @property $db83_bancoagencia
 * @property $db83_conta
 * @property $db83_dvconta
 * @property $db83_identificador
 * @property $db83_codigooperacao
 * @property $db83_tipoconta
 * @property $db83_contaplano
 */
class ContaBancaria extends Model
{
    protected $table = 'configuracoes.contabancaria';
    protected $primaryKey = 'db83_sequencial';
    public $timestamps = false;


    public function vinculoPlanoContas()
    {
        return $this->hasOne(ConplanoContaBancaria::class, 'c56_contabancaria', 'db83_sequencial');
    }

    public function agencia()
    {
        return $this->belongsTo(Agencia::class, 'db83_bancoagencia', 'db89_sequencial');
    }
}
