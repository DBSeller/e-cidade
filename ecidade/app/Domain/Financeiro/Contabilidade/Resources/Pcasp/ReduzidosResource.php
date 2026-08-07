<?php

namespace App\Domain\Financeiro\Contabilidade\Resources\Pcasp;

use App\Domain\Financeiro\Contabilidade\Models\ConplanoReduzido;
use Illuminate\Database\Eloquent\Collection;

class ReduzidosResource
{
    public static function manutencao(Collection $reduzidos, $contaBancaria = false)
    {
        return $reduzidos->map(function (ConplanoReduzido $reduzido) use ($contaBancaria) {
            $recurso = $reduzido->recurso()->first();
            $complento = $recurso->complemento()->first();
            $fonte = $recurso->fonteRecurso($reduzido->c61_anousu);

            $reduzido->conta_bancaria = null;
            if ($contaBancaria) {
                $reduzido->conta_bancaria = $reduzido->dadosBancario();
            }

            $reduzido->em_uso = $reduzido->reduzidoEstaEmUso();
            $reduzido->recurso = (object)
            [
                'codigo' =>$recurso->o15_codigo,
                'descricao' =>$recurso->o15_descr,
                'gestao' => $fonte->gestao,
                'siconfi' => $fonte->codigo_siconfi,
                'complemento' => (object) [
                    'codigo' => $complento->o200_sequencial,
                    'descricao' => $complento->o200_descricao
                ]
            ];
            return $reduzido;
        });
    }
}
