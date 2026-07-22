<?php

namespace App\Domain\Saude\ESF\Models;

use Illuminate\Database\Eloquent\Model;

class FichaVacinacaoImunobiologico extends Model
{
    protected $table = 'plugins.psf_ficha_vacinacao_imunobiologico';
    protected $primaryKey = 'psf21_id';
    public $timestamps = false;

    public function imunobiologico()
    {
        return $this->belongsTo(Imunobiologico::class, 'psf21_imunobiologico', 'psf22_id_esus');
    }
}
