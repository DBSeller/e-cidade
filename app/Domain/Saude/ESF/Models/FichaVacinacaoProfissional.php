<?php

namespace App\Domain\Saude\ESF\Models;

use App\Domain\Patrimonial\Protocolo\Model\Cgm;
use App\Domain\Saude\Ambulatorial\Models\Unidade;
use Illuminate\Database\Eloquent\Model;

class FichaVacinacaoProfissional extends Model
{
    protected $table = 'plugins.psf_ficha_vacinacao_profissional';
    protected $primaryKey = 'psf20a_id';
    public $timestamps = false;

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 'psf20a_profissional_unidade', 'sd02_i_codigo');
    }

    public function cgm()
    {
        return $this->belongsTo(Cgm::class, 'psf20a_profissional_cgm', 'z01_numcgm');
    }
}
