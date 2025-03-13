<?php

namespace App\Domain\Configuracao\Endereco\Model;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{

    protected $table = 'configuracoes.endereco';
    protected $primaryKey = 'db76_sequencial';
    protected $with = ["local"];
    public $timestamps = false;

    public function local()
    {
        return $this->belongsTo(EnderecoLocal::class, "db76_cadenderlocal");
    }
}
