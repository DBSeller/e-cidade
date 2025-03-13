<?php

namespace App\Domain\Saude\TFD\Models;

use Illuminate\Database\Eloquent\Model;

class Passageiro extends Model
{
    protected $table = 'tfd.tfd_passageiroveiculo';
    protected $primaryKey = 'tf19_i_codigo';
    public $timestamps = false;

    public function veiculoDestino()
    {
        return $this->hasOne(VeiculoDestino::class, 'tf18_i_codigo', 'tf19_i_veiculodestino');
    }
}
