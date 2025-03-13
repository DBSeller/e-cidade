<?php

namespace App\Domain\Configuracao\Endereco\Model;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'configuracoes.cadenderestado';
    protected $primaryKey = 'db71_sequencial';
    protected $with = ["pais"];
    public $timestamps = false;

    public function pais()
    {
        return $this->belongsTo(Pais::class, "db71_cadenderpais");
    }
}
