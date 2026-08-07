<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ConplanoAtributos
 * @property $c121_sequencial;
 * @property $c120_anousu
 * @property $c120_conplano
 * @property $c120_infocomplementar
 * @property $c120_conplanosistema
 *
 * @method ConplanoAtributos atributosSiconfi()
 * @method ConplanoAtributos atributosContaCorrente()
 */
class ConplanoAtributos extends Model
{
    protected $table = 'contabilidade.conplanoatributos';
    protected $primaryKey = 'c120_sequencial';
    public $timestamps = false;

    const SISTEMA_SICONFI = 1;

    public function scopeAtributosSiconfi(Builder $query)
    {
        $query->where('c120_conplanosistema', self::SISTEMA_SICONFI);
    }

    public function scopeAtributosContaCorrente(Builder $query)
    {
        $query->where('c120_conplanosistema', '!=', self::SISTEMA_SICONFI);
    }

    public function informacaoComplementar()
    {
        return $this->hasOne(InformacaoComplementar::class, 'c121_sequencial', 'c120_infocomplementar');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sistema()
    {
        return $this->hasOne(ConplanoSistema::class, 'c122_sequencial', 'c120_conplanosistema');
    }
}
