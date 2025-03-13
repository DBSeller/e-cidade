<?php

namespace App\Domain\Patrimonial\Material\Repositories;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Material\Models\MaterialEstoqueItem;

class MaterialEstoqueItemRepository extends BaseRepository
{
    protected $modelClass = MaterialEstoqueItem::class;

    /**
     * @param bool $estoqueZerado
     * @param array|\Closure $where
     * @param string $ordem
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEstoque($estoqueZerado = false, $where = null, $ordem = '', $medicamento = false)
    {
        $campos = [
            'm71_codmatestoque',
            'm60_descr',
            'sum(m71_quant) as m71_quant',
            'sum(m71_quantatend) as m71_quantatend',
            'm77_lote as lote',
            'm77_dtvalidade as data_validade'
        ];
        $groupBy = [
            'm71_codmatestoque',
            'm77_lote',
            'm77_dtvalidade',
            'm60_descr'
        ];

        if ($medicamento) {
            $campos[] = 'fa01_i_codigo as id_medicamento';
            $groupBy[] = 'fa01_i_codigo';
        }

        $query = $this->queryEstoque()
            ->when($medicamento, function ($query) {
                $query->join('far_matersaude', 'fa01_i_codmater', 'm60_codmater');
            })->selectRaw(implode(', ', $campos));

        if (!$estoqueZerado) {
            $query->whereRaw('m71_quant != m71_quantatend');
        }

        if ($where) {
            $query->where($where);
        }

        $query->orderBy($ordem != '' ? $ordem : 'm60_descr');

        return $query->groupBy($groupBy)->get();
    }

    private function queryEstoque()
    {
        return $this->newQuery()
            ->join('matestoque', 'm70_codigo', 'm71_codmatestoque')
            ->join('matmater', 'm60_codmater', 'm70_codmatmater')
            ->leftJoin('matestoqueitemlote', 'm77_matestoqueitem', 'm71_codlanc');
    }
}
