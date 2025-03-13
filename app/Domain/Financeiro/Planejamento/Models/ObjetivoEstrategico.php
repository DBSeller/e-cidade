<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ObjetivoEstrategico
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl5_codigo
 * @property $pl5_arearesultado
 * @property $pl5_titulo
 * @property $pl5_contextualizacao
 * @property $pl5_fonte
 */
class ObjetivoEstrategico extends Model
{
    const CREATED_AT = 'pl5_created_at';
    const UPDATED_AT = 'pl5_updated_at';

    protected $table = 'planejamento.objetivoestrategico';

    protected $primaryKey = 'pl5_codigo';

    protected $fillable = ['pl5_titulo', 'pl5_contextualizacao', 'pl5_fonte'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|AreaResultado
     */
    public function areaResultado()
    {
        return $this->belongsTo(AreaResultado::class, 'pl5_arearesultado', 'pl4_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function programas()
    {
        return $this->belongsToMany(
            ProgramaEstrategico::class,
            'planejamento.objetivoestrategicoprograma',
            'pl6_objetivoestrategico',
            'pl6_programaestrategico'
        );
    }
}
