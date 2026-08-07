<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PeriodoAcao
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl14_codigo
 * @property $pl14_descricao
 * @property $created_at
 * @property $updated_at
 */
class Periodo extends Model
{
    protected $table = 'planejamento.periodoacao';
    protected $primaryKey = 'pl14_codigo';

    public function iniciativas()
    {
        $this->hasMany(Iniciativa::class, 'pl12_periodoacao', 'pl14_codigo');
    }
}
