<?php

namespace App\Domain\Patrimonial\Material\Services;

use App\Domain\Patrimonial\Material\Repositories\MaterialEstoqueItemRepository;
use App\Domain\Patrimonial\Material\Requests\RelatorioRastreabilidadeMaterialRequest;

class RastreabilidadeMaterialService
{
    /**
     * @var array
     */
    protected $depositos = [];

    /**
     * @var array
     */
    protected $materiais = [];

    /**
     * @var boolean
     */
    protected $estoqueZerado = false;

    /**
     * @var integer
     */
    protected $ordem = 0;

    /**
     * @var integer
     */
    protected $quebra = 0;

    /**
     * @param RelatorioRastreabilidadeMaterialRequest $request
     * @return RastreabilidadeMaterialService
     */
    public function setFiltros(RelatorioRastreabilidadeMaterialRequest $request)
    {
        $this->depositos = $request->get('depositos', []);
        $this->materiais = $request->get('materiais', []);
        $this->estoqueZerado = $request->get('estoqueZerado', 0) == 1;
        $this->ordem = $request->get('ordem', 0);
        $this->quebra = $request->get('quebra', 0);

        return $this;
    }

    /**
     * @return array
     */
    public function buscarDados()
    {
        $repository = new MaterialEstoqueItemRepository;

        $ordem = $this->ordem == 1 ? 'm60_codmatmater' : '';

        $estoques = $repository->getEstoque($this->estoqueZerado, $this->getFiltros(), $ordem);

        if ($estoques->isEmpty()) {
            throw new \Exception('Nenhum registro encontrado.', 200);
        }

        return $this->formatar($estoques);
    }

    /**
     * @return \App\Domain\Patrimonial\Material\Contracts\RelatorioRastreabilidadeMaterial
     */
    public function getRelatorio(array $dados)
    {
        throw new \Exception('Relatório de rastreabilidade material não configurado.', 406);
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
                $query->whereIn('m70_codmatmater', $this->materiais);
            }
        };
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $estoques
     * @return array
     */
    protected function formatar(\Illuminate\Database\Eloquent\Collection $estoques)
    {
        $dados = [];

        foreach ($estoques as $estoqueItem) {
            $dados[] = $estoqueItem;
        }

        return $dados;
    }
}
