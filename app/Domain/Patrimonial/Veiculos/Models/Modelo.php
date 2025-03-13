<?php

namespace App\Domain\Patrimonial\Veiculos\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Patrimonial\Veiculos\Models
 */
class Modelo extends Model
{
    protected $table = 'veiculos.veiccadmodelo';
    protected $primaryKey = 've22_codigo';
    public $timestamps = false;
}
