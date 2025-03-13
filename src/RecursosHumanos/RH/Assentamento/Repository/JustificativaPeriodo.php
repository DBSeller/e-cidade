<?php


namespace ECidade\RecursosHumanos\RH\Assentamento\Repository;

use DBException;
use ECidade\RecursosHumanos\RH\Assentamento\Model\JustificativaPeriodo as Model;

class JustificativaPeriodo
{
    /**
     * @param Model $justificativaPeriodo
     * @throws DBException
     */
    public static function incluir(Model $justificativaPeriodo)
    {
        $dao = new \cl_assentamentojustificativaperiodo();

        $dao->rh206_codigo = $justificativaPeriodo->getcodigoAssentamento();
        $dao->rh206_periodo = $justificativaPeriodo->getPeriodo();
        $dao->incluir();

        if ($dao->erro_status == '0') {
            throw new DBException($dao->erro_msg);
        }
    }

    /**
     * @param null $codigoAssentamento
     * @return bool
     * @throws DBException
     */
    public static function excluirPorAssentamento($codigoAssentamento = null)
    {
        if (empty($codigoAssentamento)) {
            return false;
        }
        $sql = "DELETE FROM recursoshumanos.assentamentojustificativaperiodo WHERE rh206_codigo = $codigoAssentamento";
        $rs = db_query($sql);

        if (!$rs) {
            $mensagem = "Ocorreu um erro ao tentar excluir o codigo de assentamento " . $codigoAssentamento . ".";
            throw new DBException($mensagem);
        }
        return true;
    }
}
