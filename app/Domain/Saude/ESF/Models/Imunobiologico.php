<?php

namespace App\Domain\Saude\ESF\Models;

use Illuminate\Database\Eloquent\Model;

class Imunobiologico extends Model
{
    protected $table = 'plugins.imunobiologico';
    protected $primaryKey = 'psf22_id';
    public $timestamps = false;
}
