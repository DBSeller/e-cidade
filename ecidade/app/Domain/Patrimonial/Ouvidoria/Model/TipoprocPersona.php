<?php

namespace App\Domain\Patrimonial\Ouvidoria\Model;

use Illuminate\Database\Eloquent\Model;

class TipoprocPersona extends Model
{

    protected $table = 'ouvidoria.tipoprocpersona';
    protected $primaryKey = 'ov34_sequencial';
    public $timestamps = false;
    protected $fillable = [
        'ov34_persona',
        'ov34_tipoproc'
    ];
}
