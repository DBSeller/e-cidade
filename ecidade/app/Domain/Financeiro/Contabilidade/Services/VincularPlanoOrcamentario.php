<?php

namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\PlanoContas;
use App\Domain\Financeiro\Contabilidade\Models\ConplanoOrcamento;
use App\Domain\Financeiro\Contabilidade\Models\PlanoReceita;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class VincularPlanoOrcamentario
{
    /**
     * @param array $filtros
     * @return Collection|ConplanoOrcamento[]
     */
    public function getContasConplanoOrcamento(array $filtros)
    {
        return ConplanoOrcamento::query()
            ->orderBy('c60_estrut')
            ->select('*')
            ->when(!empty($filtros['exercicio']), function ($query) use ($filtros) {
                $query->where('c60_anousu', '=', $filtros['exercicio']);
            })
            ->when(isset($filtros['receita']), function ($query) {
                $query->apenasReceita();
            })
            ->when(!empty($filtros['apenasAnaliticas']), function ($query) use ($filtros) {
                $query->apenasAnaliticas();
            })
            // filtra pelo id da conta do governo no sistema (união ou estado)
            ->when(!empty($filtros['idContaVinculada']), function ($query) use ($filtros) {
                $query->contaVinculadaReceita($filtros['idContaVinculada']);
            })
            ->when(!empty($filtros['estrutural']), function ($query) use ($filtros) {
                $estruturalConta = new Estrutural($filtros['estrutural']);
                $ateNivel = $estruturalConta->getEstruturalAteNivel();
                $query->where('c60_estrut', 'like', "{$ateNivel}%");
            })
            ->get();
    }

    public function vincular($planoOrcamentario, array $idsContasEcidade)
    {
        $this->vincularContas($planoOrcamentario, $idsContasEcidade);

        return true;
    }

    /**
     * @param integer$codigo
     * @param string $tipoPlano
     * @param string $origem
     * @return bool
     */
    public function desvincular($codigo, $tipoPlano, $origem)
    {
        $conta = ConplanoOrcamento::find($codigo);

        switch ($origem) {
            case PlanoContas::ORIGEM_RECEITA:
                if ($tipoPlano === PlanoContas::PLANO_UNIAO) {
                    $conta->planoUniaoReceita()->sync([]);
                } else {
                    $conta->planoEstadualReceita()->sync([]);
                }
                break;
            case PlanoContas::ORIGEM_DESPESA:
                if ($tipoPlano === PlanoContas::PLANO_UNIAO) {
                    $conta->planoUniaoDespesa()->sync([]);
                } else {
                    $conta->planoEstadualDespesa()->sync([]);
                }
                break;
        }

        return true;
    }

    protected function vincularContas(Model $planoOrcamentario, array $idsContasEcidade)
    {
        $planoOrcamentario->contasEcidade()->sync($idsContasEcidade);
    }
}
