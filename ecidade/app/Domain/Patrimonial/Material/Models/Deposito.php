<?php

namespace App\Domain\Patrimonial\Material\Models;

use App\Domain\Configuracao\Departamento\Models\Departamento;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $m91_codigo
 * @property integer $m91_depto
 * @property Departamento $departamento
 */
class Deposito extends Model
{
    protected $table = 'material.db_almox';
    protected $primaryKey = 'm91_codigo';
    public $timestamps = false;

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'm91_depto', 'coddepto');
    }
}
