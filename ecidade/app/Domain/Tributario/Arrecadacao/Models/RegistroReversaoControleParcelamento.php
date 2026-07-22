<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroReversaoControleParcelamento extends Model
{
    protected $table = 'arrecadacao.controleparc_rollback';
    protected $primaryKey = 'ar52_id';
    public $timestamps = false;
}
