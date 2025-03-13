<?php

namespace ECidade\Integracao\Sped\ESocial\Evento;

use Avaliacao;
use cl_avaliacaogruporespostatotalizacaopagamentocontingencia;
use DBException;
use ECidade\Integracao\Sped\Common\Evento\EventoAbstract;
use Exception;
use stdClass;

class S1295 extends EventoAbstract
{
    const AVALIACAO = 4000103;

    /**
     * @param stdClass $parametros
     * @return null
     * @throws Exception
     */
    public function buscarCodigoPreenchimento(stdClass $parametros)
    {
        $dao = new cl_avaliacaogruporespostatotalizacaopagamentocontingencia();
        $where = array(
            "eso34_empregador = {$parametros->empregador}" ,
            "eso34_indicativoapuracao = {$parametros->indicativoPeriodo}" ,
            "eso34_periodo = '{$parametros->periodo}'"
        );

        $sql = $dao->sql_query_file(null, "*", 'eso34_sequencial DESC', implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o fechamento.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return pg_fetch_object($rs)->eso34_avaliacaogruporesposta;
    }

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @return int
     * @throws Exception
     */
    public function persistir(Avaliacao $avaliacao, array $parametros = array())
    {
        $parametros = (object) $parametros;
        $dao = new cl_avaliacaogruporespostatotalizacaopagamentocontingencia();
        $where = array(
            "eso34_empregador = {$parametros->empregador}" ,
            "eso34_indicativoapuracao = {$parametros->indicativoPeriodo}" ,
            "eso34_periodo = '{$parametros->periodo}'"
        );
        $sqlVerificacao = $dao->sql_query_file(null, 'eso34_sequencial', null, implode(' AND ', $where));
        $rsVerificacao = db_query($sqlVerificacao);

        if (!$rsVerificacao) {
            throw new Exception("Erro ao buscar o preenchimento.");
        }

        $dao->eso34_indicativoapuracao = $parametros->indicativoPeriodo;
        $dao->eso34_periodo = "{$parametros->periodo}";
        $dao->eso34_avaliacaogruporesposta = $avaliacao->getAvaliacaoGrupo();
        $dao->eso34_empregador = $parametros->empregador;        

        if (pg_num_rows($rsVerificacao) > 0) {
            $id = pg_fetch_object($rsVerificacao)->eso34_sequencial;
            $dao->eso34_sequencial = $id;
            $dao->alterar($id);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            die($dao->erro_msg);
            throw new DBException('Não foi possível salvar o preenchimento.');
        }

        return $dao->eso34_avaliacaogruporesposta;
    }
}