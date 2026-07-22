<?php

namespace App\Domain\Saude\TFD\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Saude\TFD\Models
 */
class AgendaSaida extends Model
{
    protected $table = 'tfd.tfd_agendasaida';
    protected $primaryKey = 'tf17_i_codigo';
    public $timestamps = false;

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'tf17_i_pedidotfd', 'tf01_i_codigo');
    }
}
