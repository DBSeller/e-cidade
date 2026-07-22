<?php

namespace App\Domain\Saude\TFD\Models;

use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    protected $table = 'tfd.tfd_destino';
    protected $primaryKey = 'tf03_i_codigo';
    public $timestamps = false;
}
