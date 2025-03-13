<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

class ProfissionalVacinacao extends Model
{
    protected $table = 'escola.rechumano_vacinacao';
    protected $primaryKey = 'ed181_codigo';
    public $timestamps = false;
    public $incrementing = true;

    public function vacina()
    {
        return $this->hasOne(Vacina::class, "ed178_codigo", "ed181_vacina");
    }

    public function dose()
    {
        return $this->hasOne(Dose::class, "ed180_codigo", "ed181_dose");
    }
}
