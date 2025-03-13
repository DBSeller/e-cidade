<?php

namespace App\Domain\Configuracao\Endereco\Model;

use Illuminate\Database\Eloquent\Model;

class Rua extends Model
{
    protected $table = 'configuracoes.cadenderrua';
    protected $primaryKey = 'db74_sequencial';
    public $timestamps = false;
}
