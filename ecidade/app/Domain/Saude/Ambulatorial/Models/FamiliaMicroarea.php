<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Model;

class FamiliaMicroarea extends Model
{
    protected $table = 'ambulatorial.familiamicroarea';
    protected $primaryKey = 'sd35_i_codigo';
    public $timestamps = false;

    public function familia()
    {
        return $this->hasOne(Familia::class, 'sd33_i_codigo', 'sd35_i_familia');
    }

    public function microarea()
    {
        return $this->hasOne(Microarea::class, 'sd34_i_codigo', 'sd35_i_microarea');
    }
}
