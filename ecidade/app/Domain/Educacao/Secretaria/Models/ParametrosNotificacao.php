<?php

namespace App\Domain\Educacao\Secretaria\Models;

use Illuminate\Database\Eloquent\Model;

class ParametrosNotificacao extends Model
{
    protected $table = "secretariadeeducacao.parametrosnotificacao";
    protected $primaryKey = "ed177_codigo";
    public $timestamps = false;
}
