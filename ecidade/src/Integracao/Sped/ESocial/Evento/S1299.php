<?php

namespace ECidade\Integracao\Sped\ESocial\Evento;

use Avaliacao;
use cl_avaliacaogruporespostaesocials1299;
use DBException;
use ECidade\Integracao\Sped\Common\Evento\EventoAbstract;
use Exception;
use stdClass;

class S1299 extends EventoAbstract
{
    const AVALIACAO = 4000106;

    /**
     * @param stdClass $parametros
     * @return null
     * @throws Exception
     */
    public function buscarCodigoPreenchimento(stdClass $parametros)
    {
        $dao = new cl_avaliacaogruporespostaesocials1299();
        $where = array(
            "eso33_empregador = {$parametros->empregador}",
            "eso33_indicativoapuracao = {$parametros->indicativoPeriodo}",
            "eso33_periodo = '{$parametros->periodo}'"
        );

        $sql = $dao->sql_query_file(null, "*", 'eso33_sequencial DESC', implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o fechamento.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return pg_fetch_object($rs)->eso33_avaliacaogruporesposta;
    }

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @return int
     * @throws Exception
     */
    public function persistir(Avaliacao $avaliacao, array $parametros = array())
    {
        $parametros = (object)$parametros;
        $dao = new cl_avaliacaogruporespostaesocials1299();
        $where = array(
            "eso33_empregador = {$parametros->empregador}",
            "eso33_indicativoapuracao = {$parametros->indicativoPeriodo}",
            "eso33_periodo = '{$parametros->periodo}'"
        );
        $sqlVerificacao = $dao->sql_query_file(null, 'eso33_sequencial', null, implode(' AND ', $where));
        $rsVerificacao = db_query($sqlVerificacao);

        if (!$rsVerificacao) {
            throw new Exception("Erro ao buscar o preenchimento.");
        }

        $dao->eso33_indicativoapuracao = $parametros->indicativoPeriodo;
        $dao->eso33_periodo = "{$parametros->periodo}";
        $dao->eso33_avaliacaogruporesposta = $avaliacao->getAvaliacaoGrupo();
        $dao->eso33_empregador = $parametros->empregador;
        $dao->eso33_avaliacao = self::AVALIACAO;

        if (pg_num_rows($rsVerificacao) > 0) {
            $id = pg_fetch_object($rsVerificacao)->eso33_sequencial;
            $dao->eso33_sequencial = $id;
            $dao->alterar($id);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new DBException('Não foi possível salvar o preenchimento.');
        }

        return $dao->eso33_avaliacaogruporesposta;
    }
}
