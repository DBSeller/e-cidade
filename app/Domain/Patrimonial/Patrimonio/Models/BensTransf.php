<?php

namespace App\Domain\Patrimonial\Patrimonio\Models;

use App\Domain\Configuracao\Departamento\Models\Departamento;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Configuracao\Usuario\Models\Usuario;

class BensTransf extends Model
{
    protected $table = "patrimonio.benstransf";
    protected $primaryKey = 't93_codtran';
    public $timestamps = false;

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 't93_depart', 'coddepto');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 't93_id_usuario', 'id_usuario');
    }
}
