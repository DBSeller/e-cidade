<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Saude\Ambulatorial\Models
 * @property integer $s135_i_codigo
 * @property integer $s135_i_prontproced
 * @property integer $s135_i_cid
 *
 * @property Cid $cid
 */
class ProcedimentoProntuarioCid extends Model
{
    public $timestamps = false;
    protected $table = 'ambulatorial.prontprocedcid';
    protected $primaryKey = 's135_i_codigo';

    public function cid()
    {
        return $this->belongsTo(Cid::class, 's135_i_cid', 'sd70_i_codigo');
    }
}
