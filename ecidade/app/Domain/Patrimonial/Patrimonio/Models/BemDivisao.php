<?php

namespace App\Domain\Patrimonial\Patrimonio\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $t33_bem
 * @property integer $t33_divisao
 */
class BemDivisao extends Model
{
    protected $table = "patrimonio.bensdiv";
    protected $primaryKey = 't33_bem';
    public $timestamps = false;
}
