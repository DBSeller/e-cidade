<?php

namespace App\Domain\Patrimonial\Patrimonio\Resources;

use App\Domain\Patrimonial\Patrimonio\Models\Bem;
use Illuminate\Database\Eloquent\Collection;

class BensResource
{
    public static function toObject(Bem $model)
    {
        return (object)[
            'codigo' => $model->t52_bem,
            'descricao' => $model->t52_descr,
            'placa' => $model->t52_ident
        ];
    }

    public static function toArray(Collection $collection)
    {
        $dados = [];
        foreach ($collection as $bem) {
            $dados[] = static::toObject($bem);
        }

        return $dados;
    }
}
