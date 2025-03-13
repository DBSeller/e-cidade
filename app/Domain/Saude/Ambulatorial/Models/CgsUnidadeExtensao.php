<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Model;

class CgsUnidadeExtensao extends Model
{
    protected $table = 'ambulatorial.cgs_und_ext';
    protected $primaryKey = 'z01_i_id';
    public $timestamps = false;

    public function cgsUnidade()
    {
        return $this->belongsTo(CgsUnidade::class, 'z01_i_cgsund', 'z01_i_cgsund');
    }
}
