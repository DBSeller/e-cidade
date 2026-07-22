<?php

namespace App\Domain\Patrimonial\Patrimonio\Models;

use App\Domain\Configuracao\Departamento\Models\Departamento;
use Illuminate\Database\Eloquent\Model;

class BensTransfDestino extends Model
{
    protected $table = "patrimonio.benstransfdes";
    public $timestamps = false;

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 't94_depart', 'coddepto');
    }

    public function divisao()
    {
        return $this->belongsTo(DivisaoDepartamento::class, 't94_divisao', 't30_codigo');
    }
}
