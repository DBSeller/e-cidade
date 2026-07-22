<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Financeiro\Tesouraria\Models\RegraParcelamento;

class AgendamentoControleParcelamento extends Model
{
    protected $table = 'arrecadacao.controleparc_agendamento';
    protected $primaryKey = 'ar49_id';
    public $timestamps = false;

    public function acao()
    {
        return $this->belongsTo(AcaoControleParcelamento::class, 'ar49_acao', 'ar50_id');
    }

    public function regraParcelamento()
    {
        return $this->belongsTo(RegraParcelamento::class, 'ar49_regra_parcelamento', 'k40_codigo');
    }
}
