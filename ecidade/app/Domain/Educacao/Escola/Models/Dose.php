<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

class Dose extends Model
{
    protected $table = 'escola.doses';
    protected $primaryKey = 'ed180_codigo';
    public $timestamps = false;
    public $incrementing = false;

    public function vacinas()
    {
        return $this->belongsToMany(Vacina::class, 'vacinas_doses', 'ed179_dose', 'ed179_vacina');
    }
}
