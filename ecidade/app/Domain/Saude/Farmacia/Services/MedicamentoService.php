<?php

namespace App\Domain\Saude\Farmacia\Services;

use App\Domain\Saude\Farmacia\Repositories\MedicamentoRepository;

class MedicamentoService
{
    /**
     * @var MedicamentoRepository
     */
    private $repository;

    public function __construct(MedicamentoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retorna a quantidade em estoque do medicamento
     * @param integer $idMedicamento
     * @param integer $idDepartamento
     *
     * @return integer
     */
    public function getEstoque($idMedicamento, $idDepartamento)
    {
        $saldoEntradaSaida = $this->repository->getSaldoEntradaSaida($idMedicamento, $idDepartamento);
        $saldoTransferencia = $this->repository->getSaldoTransferencia($idMedicamento, $idDepartamento);

        return $saldoEntradaSaida - $saldoTransferencia;
    }
}
