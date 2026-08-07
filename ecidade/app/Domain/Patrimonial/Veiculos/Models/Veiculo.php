<?php

namespace App\Domain\Patrimonial\Veiculos\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Patrimonial\Veiculos\Models
 */
class Veiculo extends Model
{
    protected $table = 'veiculos.veiculos';
    protected $primaryKey = 've01_codigo';
    public $timestamps = false;

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 've01_veiccadmodelo', 've22_codigo');
    }
}
