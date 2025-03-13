<?php

namespace App\Domain\Patrimonial\Material\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $m64_sequencial
 * @property integer $m64_almox
 * @property integer $m64_matmater
 * @property double $m64_estoqueminimo
 * @property double $m64_estoquemaximo
 * @property double $m64_pontopedido
 * @property string $m64_localizacao
 */
class MaterialLocalizacao extends Model
{
    public $timestamps = false;
    protected $table = 'material.matmaterestoque';
    protected $primaryKey = 'm64_sequencial';

    public function scopeMaterial(Builder $query, $idMaterial)
    {
        return $query->where('m64_matmater', $idMaterial);
    }

    public function scopeDeposito(Builder $query, $idDeposito)
    {
        return $query->where('m64_almox', $idDeposito);
    }

    public function scopeTemLocalizacao(Builder $query)
    {
        return $query->whereRaw('m64_localizacao is not null');
    }
}
