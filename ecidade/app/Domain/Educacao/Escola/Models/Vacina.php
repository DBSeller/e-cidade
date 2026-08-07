<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

class Vacina extends Model
{
    protected $table = 'escola.vacinas_escola';
    protected $primaryKey = 'ed178_codigo';
    public $timestamps = false;
    public $incrementing = false;

    public function doses()
    {
        return $this->belongsToMany(Dose::class, 'vacinas_doses', 'ed179_vacina', 'ed179_dose');
    }
}
