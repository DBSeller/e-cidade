<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Model;

class CensoAreasPos extends Model
{
    protected $table = 'escola.censoareaspos';
    protected $primaryKey = 'ed184_id';
    public $timestamps = false;
    public $incrementing = false;
}
