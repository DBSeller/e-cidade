<?php

namespace App\Domain\Patrimonial\Ouvidoria\Model;

use Illuminate\Database\Eloquent\Model;

class FormaReclamacao extends Model
{

    protected $table = 'ouvidoria.formareclamacao';
    protected $primaryKey = 'p42_sequencial';
    public $timestamps = false;
}
