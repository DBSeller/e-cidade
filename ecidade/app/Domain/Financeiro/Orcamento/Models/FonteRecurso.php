<?php

namespace App\Domain\Financeiro\Orcamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property integer $id
 * @property integer $orctiporec_id
 * @property integer $exercicio
 * @property string $codigo_siconfi
 * @property string $gestao
 * @property integer $classificacaofr_id
 * @property string $tipo_detalhamento
 * @property string $descricao
 */
class FonteRecurso extends Model
{
    protected $table = 'orcamento.fonterecurso';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'orctiporec_id', 'o15_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classificacao()
    {
        return $this->belongsTo(ClassificacaoFonteRecurso::class, 'classificacaofr_id', 'id');
    }
}
