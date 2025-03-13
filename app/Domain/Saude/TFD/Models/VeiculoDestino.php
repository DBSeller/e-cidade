<?php

namespace App\Domain\Saude\TFD\Models;

use App\Domain\Patrimonial\Veiculos\Models\Motorista;
use App\Domain\Patrimonial\Veiculos\Models\Veiculo;
use Illuminate\Database\Eloquent\Model;

class VeiculoDestino extends Model
{
    protected $table = 'tfd.tfd_veiculodestino';
    protected $primaryKey = 'tf18_i_codigo';
    public $timestamps = false;

    public function destino()
    {
        return $this->belongsTo(Destino::class, 'tf18_i_destino', 'tf03_i_codigo');
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'tf18_i_veiculo', 've01_codigo');
    }

    public function motorista()
    {
        return $this->belongsTo(Motorista::class, 'tf18_i_motorista', 've05_codigo');
    }
}
