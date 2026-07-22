<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

class Profissional extends Model
{
    protected $table = 'escola.rechumano';
    protected $primaryKey = 'ed20_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    public function vacinacao()
    {
        return $this->hasMany(ProfissionalVacinacao::class, 'ed181_rechumano', 'ed20_i_codigo');
    }
}
