<?php

namespace App\Domain\Patrimonial\Licitacoes\Models;

use App\Domain\Patrimonial\Compras\Models\TipoCompra;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $l03_codigo
 * @property $l03_descr
 * @property $l03_tipo
 * @property $l03_codcom
 * @property $l03_instit
 * @property $l03_usaregistropreco
 * @property $l03_pctipocompratribunal
 */
class Modalidade extends Model
{
    protected $table = 'licitacao.cflicita';
    protected $primaryKey = 'l03_codigo';
    public $timestamps = false;

    public function tipoCompra()
    {
        return $this->belongsTo(TipoCompra::class, 'l03_codcom', 'pc50_codcom');
    }
}
