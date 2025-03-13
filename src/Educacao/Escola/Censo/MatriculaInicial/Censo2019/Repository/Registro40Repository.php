<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 03/05/2019
 * Time: 08:51
 */

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Repository;


use cl_escoladiretor;
use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\DiretorCensoVO;
use ECidade\Educacao\Escola\Model\ProfissionalEscola;
use ECidade\Educacao\Escola\Repository\Repository;
use Exception;

class Registro40Repository extends Repository
{

    /**
     * @throws Exception
     */
    public function getDadosDiretor(ProfissionalEscola $profissional)
    {
        $where = array(
            "ed254_i_escola = {$profissional->getEscola()->getCodigo()}",
            "ed254_i_rechumano = {$profissional->getCodigoRecursoHumano()}",
        );
        $campos = "distinct ed254_criterioacessofuncao, ed254_especificacaocriteriooutros";
        $dao = new cl_escoladiretor();
        $sql = $dao->sql_query_file(null, $campos, null, implode(' and ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar diretor.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return DiretorCensoVO::fromState(pg_fetch_array($rs));
    }
}
