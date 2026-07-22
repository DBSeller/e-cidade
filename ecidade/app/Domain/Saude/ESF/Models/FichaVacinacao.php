<?php

namespace App\Domain\Saude\ESF\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Saude\Ambulatorial\Models\Cgs;

class FichaVacinacao extends Model
{
    protected $table = 'plugins.psf_ficha_vacinacao';
    protected $primaryKey = 'psf20_id';
    public $timestamps = false;

    public function paciente()
    {
        return $this->belongsTo(Cgs::class, 'psf20_cgs', 'z01_i_numcgs');
    }

    public function profissional()
    {
        return $this->belongsTo(FichaVacinacaoProfissional::class, 'psf20_id_profissional', 'psf20a_id');
    }

    public function vacinas()
    {
        return $this->hasMany(FichaVacinacaoImunobiologico::class, 'psf21_id_ficha', 'psf20_id');
    }
}
