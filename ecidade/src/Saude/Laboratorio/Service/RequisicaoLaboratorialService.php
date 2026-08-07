<?php

namespace ECidade\Saude\Laboratorio\Service;

use ECidade\Saude\Laboratorio\Repository\RequisicaoLaboratorialRepository;

/**
 * Class RequisicaoLaboratorialService
 *
 * @package ECidade\Saude\Laboratorio\Service
 */
class RequisicaoLaboratorialService
{
    /**
     * @var RequisicaoLaboratorialRepository
     */
    private $repository;

    /**
     * RequisicaoLaboratorialService constructor.
     * @param RequisicaoLaboratorialRepository $repository
     */
    public function __construct(RequisicaoLaboratorialRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     * @return bool|\RequisicaoLaboratorial|null
     * @throws \Exception
     */
    public function getRequisicaoLaboratorial($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param  $codigoRequisicao
     * @return array|bool
     */
    public function getMateriasPorRequisicao($codigoRequisicao)
    {
        return $this->repository->getMateriaisPorRequisicao($codigoRequisicao, new \cl_lab_requiitem());
    }

    /**
     * @param  $codigoRequisicao
     * @param  $codigoMaterial
     * @return array|bool
     */
    public function getExamesPorMaterialRequisicao(
        $codigoRequisicao,
        $codigoMaterial
    ) {
        return $this->repository->getExamesPorMaterialRequisicao(
            $codigoRequisicao,
            $codigoMaterial,
            new \cl_lab_requiitem()
        );
    }

    /**
     * @param  $codigoRequisicao
     * @return array|bool
     */
    public function getExamesPorRequisicao(
        $codigoRequisicao
    ) {
        return $this->repository->getExamesPorRequisicao(
            $codigoRequisicao,
            new \cl_lab_requiitem()
        );
    }

    /**
     * @param $codigoRequisicao
     * @return array|bool
     */
    public function getSolicitanteRequisicao($codigoRequisicao)
    {
        return $this->repository->getSolicitanteRequisicao($codigoRequisicao);
    }
}
