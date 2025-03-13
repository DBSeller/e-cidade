<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AreaResultado
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl4_codigo
 * @property $pl4_planejamento
 * @property $pl4_titulo
 * @property $pl4_contextualizacao
 */
class AreaResultado extends Model
{
    const CREATED_AT = 'pl4_created_at';
    const UPDATED_AT = 'pl4_updated_at';

    protected $table = 'planejamento.arearesultado';

    protected $primaryKey = 'pl4_codigo';

    protected $fillable = ['pl4_titulo', 'pl4_contextualizacao'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planejamento()
    {
        return $this->belongsTo(Planejamento::class, 'pl4_planejamento', 'pl2_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function objetivosEstrategicos()
    {
        return $this->hasMany(ObjetivoEstrategico::class, 'pl5_arearesultado', 'pl4_codigo');
    }

    /**
     * programas vinculado a area de resultado
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function programas()
    {
        return $this->belongsToMany(
            ProgramaEstrategico::class,
            'planejamento.arearesultadoprograma',
            'arearesultado_id',
            'programaestrategico_id'
        );
    }
}
