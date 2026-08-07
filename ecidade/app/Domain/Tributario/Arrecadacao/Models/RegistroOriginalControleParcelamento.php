<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroOriginalControleParcelamento extends Model
{
    protected $table = 'arrecadacao.controleparc_registrosorig';
    protected $primaryKey = 'ar51_id';
    public $timestamps = false;
}
