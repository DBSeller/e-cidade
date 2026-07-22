<?php

namespace App\Domain\Configuracao\Endereco\Model;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'configuracoes.cadenderpais';
    protected $primaryKey = 'db70_sequencial';
    public $timestamps = false;
}
