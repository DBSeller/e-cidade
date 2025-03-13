<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

class AcaoControleParcelamento extends Model
{
    protected $table = 'arrecadacao.controleparc_acao';
    protected $primaryKey = 'ar50_id';
    public $timestamps = false;
}
