<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Origem
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl13_codigo
 * @property $pl13_descricao
 * @property $created_at
 * @property $updated_at
 */
class Origem extends Model
{
    protected $table = 'planejamento.origeminiciativa';
    protected $primaryKey = 'pl13_codigo';

    public function iniciativas()
    {
        $this->hasMany(Iniciativa::class, 'pl12_origeminiciativa', 'pl13_codigo');
    }
}
