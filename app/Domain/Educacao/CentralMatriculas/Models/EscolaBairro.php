<?php

namespace App\Domain\Educacao\CentralMatriculas\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EscolaBairro
 * @package App\Domain\Educacao\CentralMatriculas\Models
 * @property integer $mo08_codigo
 * @property integer $mo08_escola
 * @property integer $mo08_bairro
 */
class EscolaBairro extends Model
{
    protected $table = "plugins.escbairro";
    protected $primaryKey = 'mo08_codigo';
    public $timestamps = false;
    public $incrementing = false;
}
