<?php

namespace ECidade\Integracao\Sped\EFDReinf\Evento;

use Avaliacao;
use cl_avaliacaogruporespostacontribuinte;
use DBException;
use ECidade\Integracao\Sped\Common\Evento\EventoAbstract;
use Exception;
use stdClass;

class R1000 extends EventoAbstract
{
    const AVALIACAO = 3000034;

    /**
     * @param stdClass $parametros
     * @return null
     * @throws Exception
     */
    public function buscarCodigoPreenchimento(stdClass $parametros)
    {
        $dao = new cl_avaliacaogruporespostacontribuinte();

        $where = array(
            "eso27_cgm = {$this->cgm->getCodigo()}"
        );

        $sql = $dao->sql_query_file(null, "*", 'eso27_sequencial DESC', implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os preenchimentos anteriores do contribuinte.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return pg_fetch_object($rs)->eso27_avaliacaogruporesposta;
    }

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @return int
     * @throws Exception
     */
    public function persistir(Avaliacao $avaliacao, array $parametros = array())
    {
        $dao = new cl_avaliacaogruporespostacontribuinte();
        $whereVinculo = "eso27_cgm = {$this->cgm->getCodigo()}";
        $sqlVerificacao = $dao->sql_query_file(null, '1', null, $whereVinculo);
        $rsVerificacao = db_query($sqlVerificacao);

        if (!$rsVerificacao) {
            throw new Exception("Não foi possível buscar o preenchimento anterior.\nContate o suporte.");
        }

        $dao->eso27_avaliacaogruporesposta = $avaliacao->getAvaliacaoGrupo();
        $dao->eso27_cgm = $this->cgm->getCodigo();

        if (pg_num_rows($rsVerificacao) > 0) {
            $dao->alterar(null, $whereVinculo);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status === '0') {
            throw new DBException('Não foi possível salvar o preenchimento.');
        }

        return $dao->eso27_avaliacaogruporesposta;
    }
}