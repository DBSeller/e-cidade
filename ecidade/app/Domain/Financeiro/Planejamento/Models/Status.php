<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Status
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl1_codigo
 * @property $pl1_descricao
 */
class Status extends Model
{
    protected $table = 'planejamento.status';
    protected $primaryKey = 'pl1_codigo';

    protected $casts = [
        'pl1_codigo' => 'integer',
        'pl1_descricao' => 'string',
    ];
}
