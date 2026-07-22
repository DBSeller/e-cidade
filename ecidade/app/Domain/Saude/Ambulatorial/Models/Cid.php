<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Saude\Ambulatorial\Models
 * @property integer $sd70_i_codigo
 * @property string $sd70_c_cid
 * @property string $sd70_c_nome
 * @property integer $sd70_i_agravo
 * @property string $sd70_c_sexo
 *
 * @property \Illuminate\Database\Eloquent\Collection $problemas
 */
class Cid extends Model
{
    public $timestamps = false;
    protected $table = 'ambulatorial.sau_cid';
    protected $primaryKey = 'sd70_i_codigo';

    public function problemas()
    {
        return $this->belongsToMany(Problema::class, 'ambulatorial.problemacid', 's172_cid', 's172_problema')
            ->using(ProblemaCid::class);
    }
}
