<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class FatorCorrecaoDespesa
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl7_codigo
 * @property $pl7_planejamento
 * @property $pl7_orcelemento
 * @property $pl7_anoorcamento
 * @property $pl7_exercicio
 * @property $pl7_percentual
 * @property $created_at
 * @property $updated_at
 * @property $deflator
 */
class FatorCorrecaoDespesa extends Model
{
    protected $table = 'planejamento.fatorcorrecaodespesa';

    protected $primaryKey = 'pl7_codigo';

    protected $guarded = ['pl7_codigo'];

    protected $casts = [
        'pl7_percentual' => 'float'
    ];

    /**
     * Armazena as instancias em um cache para reuso
     * @var array
     */
    protected $storage = [];

    public function getPlanejamento()
    {
        if (!array_key_exists('planejamento', $this->storage)) {
            $this->storage['planejamento'] = $this->planejamento;
        }

        return $this->storage['planejamento'];
    }

    /**
     * @return BelongsTo
     */
    public function planejamento()
    {
        return $this->belongsTo(Planejamento::class, 'pl7_planejamento', 'pl2_codigo');
    }
}
