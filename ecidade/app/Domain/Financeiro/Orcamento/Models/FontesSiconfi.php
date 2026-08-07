<?php

namespace App\Domain\Financeiro\Orcamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *  @property string $codigo_siconfi
 *  @property string $descricao
 *  @property integer $classificacaofr_id
 */
class FontesSiconfi extends Model
{
    protected $table = 'orcamento.fontesiconfi';
    protected $primaryKey = 'codigo_siconfi';
    public $timestamps = false;
    public $incrementing = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classificacao()
    {
        return $this->belongsTo(ClassificacaoFonteRecurso::class, 'classificacaofr_id', 'id');
    }
}
