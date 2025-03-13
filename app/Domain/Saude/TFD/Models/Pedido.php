<?php

namespace App\Domain\Saude\TFD\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'tfd.tfd_pedidotfd';
    protected $primaryKey = 'tf01_i_codigo';
    public $timestamps = false;

    public function situacaoPedido()
    {
        return $this->hasMany(SituacaoPedido::class, 'tf28_i_pedidotfd', 'tf01_i_codigo');
    }

    public function passageiros()
    {
        return $this->hasMany(Passageiro::class, 'tf19_i_pedidotfd', 'tf01_i_codigo');
    }
}
