<?php

namespace App\Domain\Saude\Farmacia\Models;

use App\Domain\Patrimonial\Material\Models\Material;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $fa01_i_codigo
 * @property string $fa01_t_obs
 * @property integer $fa01_i_codmater
 * @property integer $fa01_i_class
 * @property string $fa01_c_nomegenerico
 * @property integer $fa01_i_precisaomed
 * @property integer $fa01_i_classemed
 * @property integer $fa01_i_listacontroladomed
 * @property integer $fa01_i_medrefemed
 * @property integer $fa01_i_laboratoriomed
 * @property integer $fa01_i_formafarmaceuticamed
 * @property integer $fa01_i_concentracaomed
 * @property integer $fa01_i_medhiperdia
 * @property string $fa01_codigobarras
 * @property integer $fa01_medicamentos
 *
 * @property Material $material
 */
class Medicamento extends Model
{
    protected $table = 'farmacia.far_matersaude';
    protected $primaryKey = 'fa01_i_codigo';
    public $timestamps = false;

    public function material()
    {
        return $this->belongsTo(Material::class, 'fa01_i_codmater', 'm60_codmater');
    }
}
