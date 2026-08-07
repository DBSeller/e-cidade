<?php

namespace App\Domain\Patrimonial\Compras\Models;

use App\Domain\Patrimonial\Licitacoes\Models\Modalidade;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $pc50_codcom
 * @property $pc50_descr
 * @property $pc50_pctipocompratribunal
 * @property $pc50_ativo
 */
class TipoCompra extends Model
{
    protected $table = 'compras.pctipocompra';
    protected $primaryKey = 'pc50_codcom';
    public $timestamps = false;
    public $incrementing = false;

    public function modalidades()
    {
        return $this->hasMany(Modalidade::class, 'l03_codcom', 'pc50_codcom');
    }
}
