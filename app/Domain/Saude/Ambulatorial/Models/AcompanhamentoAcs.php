<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Model;

class AcompanhamentoAcs extends Model
{
    protected $table = 'ambulatorial.acompanhamento_acs';
    protected $primaryKey = 's168_id';
    public $timestamps = false;

    public function unidade()
    {
        return $this->belongsTo(Unidade::class, 's168_unidade', 'sd02_i_codigo');
    }

    public function profissional()
    {
        return $this->belongsTo(Profissional::class, 's168_profissional', 'sd03_i_codigo');
    }

    public function paciente()
    {
        return $this->belongsTo(CgsUnidade::class, 's168_paciente', 'z01_i_cgsund');
    }
}
