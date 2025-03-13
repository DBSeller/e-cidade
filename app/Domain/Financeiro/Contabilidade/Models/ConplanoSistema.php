<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConplanoSistema
 * Essa model é referente aos sistemas de conta existente no e-Cidade
 * Aqui temos mapeados os atributos da conta corrente e matriz
 * Cada conjunto da conta corrente representa um ou mais atributos
 */
class ConplanoSistema extends Model
{
    protected $table = 'contabilidade.conplanosistema';
    protected $primaryKey = 'c122_sequencial';
    public $timestamps = false;

    const TIPO_SICONFI = 1;
    const TIPO_CONTA_CORRENTE = 2;

    /**
     * Atributos vínculados na conta da contabilidade
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function atributosConplano()
    {
        return $this->hasMany(ConplanoAtributos::class, 'c120_conplanosistema', 'c122_sequencial');
    }

    /**
     * atributos do sistema de conta (conta corrente)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function atributos()
    {
        return $this->hasMany(ConplanoSistemaAtributos::class, 'c129_conplanosistema', 'c122_sequencial')
            ->orderBy('c129_ordem');
    }
}
