<?php

namespace App\Domain\Patrimonial\Material\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $m60_codmater
 * @property string $m60_descr
 * @property integer $m60_codmatunid
 * @property float $m60_quantent
 * @property string $m60_codant
 * @property boolean $m60_ativo
 * @property integer $m60_controlavalidade
 * @property boolean $m60_servico
 *
 * @property UnidadeMaterial $unidade
 */
class Material extends Model
{
    protected $table = 'material.matmater';
    protected $primaryKey = 'm60_codmater';
    public $timestamps = false;

    public function unidade()
    {
        return $this->belongsTo(UnidadeMaterial::class, 'm60_codmatunid', 'm61_codmatunid');
    }
}
