<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConplanoSistemaAtributos
 * Vínculo do sistema de contas com seus atributos
 *
 * @property $c129_sequencial
 * @property $c129_conplanosistema
 * @property $c129_conplanoinfocomplementar
 * @property $c129_ordem
 */
class ConplanoSistemaAtributos extends Model
{
    protected $table = 'contabilidade.conplanosistemaatributos';
    protected $primaryKey = 'c129_sequencial';
    public $timestamps = false;

    public function sistema()
    {
        return $this->belongsTo(ConplanoSistema::class, 'c129_conplanosistema', 'c122_sequencial');
    }

    public function atributo()
    {
        return $this->belongsTo(InformacaoComplementar::class, 'c129_conplanoinfocomplementar', 'c121_sequencial');
    }
}
