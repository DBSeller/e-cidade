<?php

namespace App\Domain\Saude\Farmacia\Repositories;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Saude\Farmacia\Models\Medicamento;

class MedicamentoRepository extends BaseRepository
{
    protected $modelClass = Medicamento::class;

    /**
     * @param integer $idMedicamento
     * @param integer $idDepartamento
     *
     * @return Medicamento
     */
    public function getSaldoEntradaSaida($idMedicamento, $idDepartamento)
    {
        return $this->newQuery()
            ->selectRaw('coalesce(SUM(coalesce(m71_quant, 0) - coalesce(m71_quantatend, 0)), 0) as saldo')
            ->join('material.matestoque', 'matestoque.m70_codmatmater', 'far_matersaude.fa01_i_codmater')
            ->join('material.matestoqueitem', 'matestoqueitem.m71_codmatestoque', 'matestoque.m70_codigo')
            ->where('matestoque.m70_coddepto', $idDepartamento)
            ->where('far_matersaude.fa01_i_codigo', $idMedicamento)
            ->whereRaw('m71_quant != m71_quantatend')
            ->first()
            ->saldo;
    }

    /**
     * @param integer $idMedicamento
     * @param integer $idDepartamento
     *
     * @return Medicamento
     */
    public function getSaldoTransferencia($idMedicamento, $idDepartamento)
    {
        return $this->newQuery()
            ->selectRaw('coalesce(sum(coalesce(case when m81_tipo = 4 then m82_quant end, 0)), 0) as saldo')
            ->join('material.matestoque', 'matestoque.m70_codmatmater', 'far_matersaude.fa01_i_codmater')
            ->join('material.matestoqueitem', 'matestoqueitem.m71_codmatestoque', 'matestoque.m70_codigo')
            ->join('material.matestoqueinimei', 'matestoqueinimei.m82_matestoqueitem', 'matestoqueitem.m71_codlanc')
            ->join('material.matestoqueini', 'matestoqueini.m80_codigo', 'matestoqueinimei.m82_matestoqueini')
            ->join('material.matestoquetipo', 'matestoquetipo.m81_codtipo', 'matestoqueini.m80_codtipo')
            ->leftJoin('material.matestoqueinil', 'matestoqueinil.m86_matestoqueini', 'matestoqueini.m80_codigo')
            ->where('far_matersaude.fa01_i_codigo', $idMedicamento)
            ->where('matestoque.m70_coddepto', $idDepartamento)
            ->where('matestoquetipo.m81_codtipo', 1)
            ->whereRaw('matestoqueinil.m86_matestoqueini IS NULL')
            ->first()
            ->saldo;
    }
}
