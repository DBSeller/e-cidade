<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use App\Domain\Financeiro\Contabilidade\Builder\EstruturalPadraoReceita;
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
 * @property $categoria
 * @property $origem
 * @property $especie
 * @property $desdobramento1
 * @property $desdobramento2
 * @property $desdobramento3
 * @property $tipo
 * @property $desdobramento4
 * @property $desdobramento5
 * @property $desdobramento6
 */
class PlanoReceita extends Model
{
    protected $table = 'contabilidade.planoreceita';

    public function toArray()
    {
        $estrutural = new EstruturalPadraoReceita($this->conta, $this->classe);
        $data = parent::toArray();
        $data['mascara'] = $estrutural->estruturalComMascara();
        $data['estruturalAteNivel'] = $estrutural->estruturalAteNivel();

        return $data;
    }

    public function contasEcidade()
    {
        return $this->belongsToMany(
            ConplanoOrcamento::class,
            'contabilidade.planoreceitaconplanoorcamento',
            'planoreceita_id',
            'conplanoorcamento_codigo'
        );
    }
}
