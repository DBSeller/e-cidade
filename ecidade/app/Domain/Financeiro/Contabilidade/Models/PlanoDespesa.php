<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $exercicio
 * @property $uniao
 * @property $conta
 * @property $nome
 * @property $funcao
 * @property $sintetica
 * @property $classe
 * @property $grupo
 * @property $modalidade
 * @property $elemento
 * @property $subelemento
 * @property $desdobramento1
 * @property $desdobramento2
 * @property $desdobramento3
 * @property $created_at
 * @property $updated_at
 */
class PlanoDespesa extends Model
{
    protected $table = 'contabilidade.planodespesa';

    public function contasEcidade()
    {
        return $this->belongsToMany(
            ConplanoOrcamento::class,
            'contabilidade.planodespesaconplanoorcamento',
            'planodespesa_id',
            'conplanoorcamento_codigo'
        );
    }
}
