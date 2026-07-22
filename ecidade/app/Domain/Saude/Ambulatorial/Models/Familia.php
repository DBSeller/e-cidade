<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Model;

class Familia extends Model
{
    protected $table = 'ambulatorial.familia';
    protected $primaryKey = 'sd33_i_codigo';
    public $timestamps = false;

    public function familiamicroarea()
    {
        return $this->belongsToMany(FamiliaMicroarea::class, 'sd33_i_codigo', 'sd35_i_familia');
    }
}
