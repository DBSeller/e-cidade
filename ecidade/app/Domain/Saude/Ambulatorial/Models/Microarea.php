<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Model;

class Microarea extends Model
{
    protected $table = 'ambulatorial.microarea';
    protected $primaryKey = 'sd34_i_codigo';
    public $timestamps = false;

    public function familiamicroarea()
    {
        return $this->belongsToMany(FamiliaMicroarea::class, 'sd34_i_codigo', 'sd35_i_microarea');
    }
}
