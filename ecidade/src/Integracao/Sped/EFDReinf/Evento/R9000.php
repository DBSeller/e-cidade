<?php

namespace ECidade\Integracao\Sped\EFDReinf\Evento;

use Avaliacao;
use cl_avaliacaogruporespostaexclusaoeventosefd;
use DBException;
use ECidade\Integracao\Sped\Common\Evento\EventoAbstract;
use Exception;
use stdClass;

class R9000 extends EventoAbstract
{
    const AVALIACAO = 3000038;

    /**
     * @param stdClass $parametros
     * @return null
     * @throws Exception
     */
    public function buscarCodigoPreenchimento(stdClass $parametros)
    {
        $dao = new cl_avaliacaogruporespostaexclusaoeventosefd();

        if (empty($parametros->protocolo)) {
            throw new Exception('Número do protocolo é de preenchimento obrigatório.');
        }

        $where = array(
            "eso29_cgm = {$this->cgm->getCodigo()}",
            "eso29_protocolo = '{$parametros->protocolo}'",
        );

        $sql = $dao->sql_query_file(null, "*", null, implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os preenchimentos anteriores.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return pg_fetch_object($rs)->eso29_avaliacaogruporesposta;
    }

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @return int
     * @throws Exception
     */
    public function persistir(Avaliacao $avaliacao, array $parametros = array())
    {
        if (empty($parametros['nrRecEvt'])) {
            throw new Exception('Número do protocolo é de preenchimento obrigatório.');
        }

        if (empty($parametros['perApur'])) {
            throw new Exception('Período é de preenchimento obrigatório.');
        }

        $dao = new cl_avaliacaogruporespostaexclusaoeventosefd();
        $whereVinculo = array(
            "eso29_cgm = {$this->cgm->getCodigo()}",
            "eso29_protocolo = '{$parametros['nrRecEvt']}'"
        );
        $sqlVerificacao = $dao->sql_query_file(null, '1', null, implode(' AND ', $whereVinculo));
        $rsVerificacao = db_query($sqlVerificacao);

        if (!$rsVerificacao) {
            throw new Exception("Não foi possível buscar o preenchimento anterior.\nContate o suporte.");
        }

        $dao->eso29_avaliacaogruporesposta = $avaliacao->getAvaliacaoGrupo();
        $dao->eso29_cgm = $this->cgm->getCodigo();
        $dao->eso29_protocolo = $parametros['nrRecEvt'];
        $dao->eso29_periodo = $parametros['perApur'];

        if (pg_num_rows($rsVerificacao) > 0) {
            $dao->alterar(null, implode(' AND ', $whereVinculo));
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new DBException('Não foi possível salvar o preenchimento.');
        }

        return $dao->eso29_avaliacaogruporesposta;
    }
}