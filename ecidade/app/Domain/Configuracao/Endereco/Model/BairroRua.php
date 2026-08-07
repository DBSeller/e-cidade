<?php

namespace App\Domain\Configuracao\Endereco\Model;

use Illuminate\Database\Eloquent\Model;

class BairroRua extends Model
{

    protected $table = 'configuracoes.cadenderbairrocadenderrua';
    protected $primaryKey = 'db87_sequencials';
    public $timestamps = false;
    protected $with = ["bairro"];

    public function bairro()
    {
        return $this->belongsTo(Bairro::class, "db87_cadenderbairro");
    }
}
