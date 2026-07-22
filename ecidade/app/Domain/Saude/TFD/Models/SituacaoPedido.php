<?php

namespace App\Domain\Saude\TFD\Models;

use Illuminate\Database\Eloquent\Model;

class SituacaoPedido extends Model
{
    protected $table = 'tfd.tfd_situacaopedidotfd';
    protected $primaryKey = 'tf28_i_codigo';
    public $timestamps = false;
}
