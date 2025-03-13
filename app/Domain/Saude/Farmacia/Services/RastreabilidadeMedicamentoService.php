<?php

namespace App\Domain\Saude\Farmacia\Services;

use App\Domain\Saude\Farmacia\Relatorios\RastreabilidadeMedicamentoPdf;
use App\Domain\Patrimonial\Material\Services\RastreabilidadeMaterialService;
use App\Domain\Patrimonial\Material\Repositories\MaterialEstoqueItemRepository;
use App\Domain\Saude\Farmacia\Builders\RastreabilidadeMedicamentoBuilder;

class RastreabilidadeMedicamentoService extends RastreabilidadeMaterialService
{
    /**
     * @return \App\Domain\Patrimonial\Material\Contracts\RelatorioRastreabilidadeMaterial
     */
    public function getRelatorio(array $estoques)
    {
        return new RastreabilidadeMedicamentoPdf($estoques, $this->quebra);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function buscarDados()
    {
        $repository = new MaterialEstoqueItemRepository;

        $ordem = $this->ordem == 1 ? 'fa01_i_codigo' : '';

        $estoques = $repository->getEstoque($this->estoqueZerado, $this->getFiltros(), $ordem, true);
        if ($estoques->isEmpty()) {
            throw new \Exception('Nenhum registro encontrado.', 200);
        }

        return $this->formatar($estoques);
    }

    /**
     * @return \Closure
     */
    protected function getFiltros()
    {
        return function ($query) {
            if (count($this->depositos)) {
                $query->whereIn('m70_coddepto', $this->depositos);
            }
            if (count($this->materiais)) {
                $query->whereIn('fa01_i_codigo', $this->materiais);
            }
        };
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $estoques
     * @return array
     */
    protected function formatar(\Illuminate\Database\Eloquent\Collection $estoques)
    {
        $builder = new RastreabilidadeMedicamentoBuilder;
        $builder->setEstoques($estoques);
        $builder->setAgrupamento($this->quebra);

        return $builder->build();
    }
}
