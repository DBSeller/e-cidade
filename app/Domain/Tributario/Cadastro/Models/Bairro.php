<?php

namespace App\Domain\Tributario\Cadastro\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Domain\Tributario\Cadastro\Models\Bairro
 *
 * @method static Builder|Bairro nome($nome)
 */
class Bairro extends Model
{
    protected $table = "bairro";

    /**
     * Filtra o bairro pelo nome
     * @param Builder $query
     * @param $nome
     * @return Builder
     */
    public function scopeNome(Builder $query, $nome)
    {
        return $query->whereRaw(
            "upper(to_ascii(j13_descr)) = ?",
            [strtoupper(\DBString::removerCaracteresEspeciaisAcentos($nome))]
        );
    }
}
