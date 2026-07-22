<?php

namespace App\Domain\Patrimonial\Veiculos\Models;

use App\Domain\Patrimonial\Protocolo\Model\Cgm;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Patrimonial\Veiculos\Models
 */
class Motorista extends Model
{
    protected $table = 'veiculos.veicmotoristas';
    protected $primaryKey = 've05_codigo';
    public $timestamps = false;

    public function cgm()
    {
        return $this->belongsTo(Cgm::class, 've05_numcgm', 'z01_numcgm');
    }
}
