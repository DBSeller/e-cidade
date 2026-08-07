<?php

namespace App\Domain\Patrimonial\Ouvidoria\Model;

use App\Domain\Patrimonial\Protocolo\Model\Processo\TipoProcesso;
use Illuminate\Database\Eloquent\Model;

class TipoProcessoFormaReclamacao extends Model
{

    protected $table = 'ouvidoria.tipoprocformareclamacao';
    protected $primaryKey = 'p43_sequencial';
    public $timestamps = false;

    public function formaReclamacao()
    {
        return $this->belongsTo(FormaReclamacao::class, "p43_formareclamacao");
    }
}
