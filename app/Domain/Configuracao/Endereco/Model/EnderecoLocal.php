<?php

namespace App\Domain\Configuracao\Endereco\Model;

use Illuminate\Database\Eloquent\Model;

class EnderecoLocal extends Model
{
    protected $table = 'configuracoes.cadenderlocal';
    protected $primaryKey = 'db75_sequencial';
    protected $with = ["bairroRua"];
    public $timestamps = false;


    public function bairroRua()
    {
        return $this->belongsTo(BairroRua::class, "db75_cadenderbairrocadenderrua", "db87_sequencial");
    }
}
