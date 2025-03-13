<?php

namespace App\Domain\Saude\Ambulatorial\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Saude\Ambulatorial\Models
 * @property integer $z01_i_numcgs
 * @property integer $z01_i_tiposangue
 * @property integer $z01_i_fatorrh
 * @property string $z01_c_cartaosus
 * @property string $z01_v_familia
 * @property string $z01_v_microarea
 * @property string $z01_c_municipio
 *
 * @property CgsUnidade $cgsUnidade
 * @property \Illuminate\Database\Eloquent\Collection $cartoesSus
 * @property CartaoSus $cartaoSusDefinitivo
 */
class Cgs extends Model
{
    protected $table = 'ambulatorial.cgs';
    protected $primaryKey = 'z01_i_numcgs';
    public $timestamps = false;

    public function cgsUnidade()
    {
        return $this->hasOne(CgsUnidade::class, 'z01_i_cgsund', 'z01_i_numcgs');
    }

    public function cartoesSus()
    {
        return $this->hasMany(CartaoSus::class, 's115_i_cgs', 'z01_i_numcgs')->orderByRaw('s115_i_codigo desc');
    }

    public function cartaoSusDefinitivo()
    {
        return $this->hasOne(CartaoSus::class, 's115_i_cgs', 'z01_i_numcgs')
            ->where('s115_c_tipo', '=', 'D')
            ->orderByRaw('s115_i_codigo desc');
    }
}
