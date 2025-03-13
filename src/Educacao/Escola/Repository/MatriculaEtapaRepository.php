<?php

namespace ECidade\Educacao\Escola\Repository;

use cl_matriculaserie;
use ECidade\Educacao\Escola\Model\MatriculaEtapa;
use Exception;

/**
 * Class MatriculaEtapaRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class MatriculaEtapaRepository extends Repository
{
    /**
     * @param MatriculaEtapa $matriculaEtapa
     * @return MatriculaEtapa
     * @throws Exception
     */
    public function salvar(MatriculaEtapa $matriculaEtapa)
    {
        $daoMatriculaSerie = new cl_matriculaserie();
        $daoMatriculaSerie->ed221_i_codigo = $matriculaEtapa->getCodigo();
        $daoMatriculaSerie->ed221_i_matricula = $matriculaEtapa->getMatricula()->getCodigo();
        $daoMatriculaSerie->ed221_i_serie = $matriculaEtapa->getEtapa()->getCodigo();
        $daoMatriculaSerie->ed221_c_origem = $matriculaEtapa->getOrigem();

        if (empty($daoMatriculaSerie->ed221_i_codigo)) {
            $daoMatriculaSerie->incluir(null);
        } else {
            $daoMatriculaSerie->alterar($daoMatriculaSerie->ed221_i_codigo);
        }

        if ($daoMatriculaSerie->erro_status == 0) {
            throw new Exception("Erro ao salvar matricula serie.");
        }

        $matriculaEtapa->setCodigo($daoMatriculaSerie->ed221_i_codigo);

        return $matriculaEtapa;
    }
}
