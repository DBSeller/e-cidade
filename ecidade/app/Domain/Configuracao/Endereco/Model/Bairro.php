<?php

namespace App\Domain\Configuracao\Endereco\Model;

use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    protected $table = 'configuracoes.cadenderbairro';
    protected $primaryKey = 'db73_sequencial';
    protected $with = ["municipio"];
    public $timestamps = false;

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, "db73_cadendermunicipio");
    }
}
