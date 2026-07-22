<?php

namespace App\Domain\Financeiro\Orcamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $descricao
 */
class ClassificacaoFonteRecurso extends Model
{
    protected $table = 'orcamento.classificacaofr';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fontesSiconfi()
    {
        return $this->hasMany(FontesSiconfi::class, 'classificacaofr_id', 'id')
            ->orderBy('codigo_siconfi');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fonteRecurso()
    {
        return $this->hasMany(FonteRecurso::class, 'classificacaofr_id', 'id');
    }
}
