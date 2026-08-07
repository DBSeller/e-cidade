<?php

namespace App\Domain\Tributario\Cadastro\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Domain\Tributario\Cadastro\Models\Ruas
 *
 * @method static Builder|Ruas nome($nome)
 */
class Ruas extends Model
{
    protected $table = "ruas";

    /**
     * Filtra a rua pelo nome
     * @param Builder $query
     * @param $nome
     * @return Builder
     */
    public function scopeNome(Builder $query, $nome)
    {
        return $query->whereRaw(
            "upper(to_ascii(j14_nome)) = ?",
            [strtoupper(\DBString::removerCaracteresEspeciaisAcentos($nome))]
        );
    }
}
