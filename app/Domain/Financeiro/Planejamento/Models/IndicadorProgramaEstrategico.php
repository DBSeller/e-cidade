<?php

namespace App\Domain\Financeiro\Planejamento\Models;

use App\Domain\Financeiro\Orcamento\Models\Indicador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class IndicadorProgramaEstrategico
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl22_codigo
 * @property $pl22_programaestrategico
 * @property $pl22_orcindica
 * @property $pl22_ano
 * @property $pl22_indice
 * @property $created_at
 * @property $updated_at
 */
class IndicadorProgramaEstrategico extends Model
{
    protected $table = 'planejamento.indicadoresprogramaestrategico';
    protected $primaryKey = 'pl22_codigo';
    
    public function programaEstrategico()
    {
        return $this->belongsTo(ProgramaEstrategico::class, 'pl22_programaestrategico', 'pl9_codigo');
    }
    public function indicador()
    {
        return $this->hasOne(Indicador::class, 'o10_indica', 'pl22_orcindica');
    }
}
