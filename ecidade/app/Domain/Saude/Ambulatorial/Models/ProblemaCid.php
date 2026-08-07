<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @package App\Domain\Saude\Ambulatorial\Models
 * @property integer $s172_id
 * @property integer $s172_problema
 * @property integer $s172_cid
 */
class ProblemaCid extends Pivot
{
    public $timestamps = false;
    protected $table = 'ambulatorial.problemacid';
    protected $primaryKey = 's172_id';
}
