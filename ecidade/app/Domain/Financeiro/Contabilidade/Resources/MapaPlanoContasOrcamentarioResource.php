<?php

namespace App\Domain\Financeiro\Contabilidade\Resources;

use App\Domain\Financeiro\Contabilidade\Models\ConplanoOrcamento;
use Illuminate\Database\Eloquent\Collection;

class MapaPlanoContasOrcamentarioResource
{
    /**
     * @param Collection $contasOrcamento Contas no padrão do estrutural
     * @param Collection $contasVinculadas Contas que já estão vinculadas
     * @return array
     */
    public static function toArrayMergeVinculadas($contasOrcamento, $contasVinculadas)
    {
        $dados = [];
        // percorre as contas que possue o mesmo padrão de estrutural da conta do plano de governo
        $contasOrcamento->each(function (ConplanoOrcamento $contaOrcamento) use (&$dados, $contasVinculadas) {
            $estrutural = $contaOrcamento->c60_estrut;

            $vinculada = $contasVinculadas->filter(function (ConplanoOrcamento $contaVinculada) use ($estrutural) {
                return $contaVinculada->c60_estrut === $estrutural;
            })->count() > 0;

            $dados[$estrutural] = self::toData($contaOrcamento, $vinculada);
        });


        // percorre as contas vinculadas a conta do plano do governo que podem não estar no padrão de estrutural
        $contasVinculadas->each(function (ConplanoOrcamento $contaVinculada) use (&$dados) {
            if (!array_key_exists($contaVinculada->c60_estrut, $dados)) {
                $dados[$contaVinculada->c60_estrut] = self::toData($contaVinculada, true);
            }
        });
        ksort($dados);
        return $dados;
    }

    public static function toArray(Collection $contasOrcamento)
    {
        $dados = [];
        $contasOrcamento->each(function (ConplanoOrcamento $contaOrcamento) use (&$dados) {
            $dados[$contaOrcamento->c60_estrut] = self::toData($contaOrcamento, false);
        });
        ksort($dados);
        return $dados;
    }

    public static function toData(ConplanoOrcamento $contaOrcamento, $vinculada = false, $contasVinculadas = null)
    {
        $contaVinculada = [];
        if (!is_null($contasVinculadas)) {
            $contaVinculada = $contasVinculadas->toArray();
        }
        return [
            "codigo" => $contaOrcamento->c60_codigo,
            "codcon" => $contaOrcamento->c60_codcon,
            "exercicio" => $contaOrcamento->c60_anousu,
            "estrutural" => $contaOrcamento->c60_estrut,
            "descricao" => $contaOrcamento->c60_descr,
            "vinculada" => $vinculada,
            "contas_vinculadas" => $contaVinculada
        ];
    }
}
