<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Integracao\Sped\EFDReinf\Evento;

use Avaliacao;
use cl_avaliacaogruporespostaretencaoservicostomados;
use db_utils;
use ECidade\Integracao\Sped\Common\Avaliacao\AvaliacaoSped;
use ECidade\Integracao\Sped\Common\Evento\EventoAbstract;
use Exception;
use stdClass;

class R2010 extends EventoAbstract
{
    const AVALIACAO = 3000039;

    const ADMINISTRATIVO = 1;
    const JURIDICO = 2;

    private $tipos = array(
        self::ADMINISTRATIVO => 'Adminstrativo',
        self::JURIDICO => 'Juridico'
    );

    /**
     * @param stdClass $parametros
     */
    public function buscarCodigoPreenchimento(stdClass $parametros)
    {
        if (empty($parametros->preenchimento)) {
            return null;
        }

        $dao = new cl_avaliacaogruporespostaretencaoservicostomados();
        $where = array(
            "efd04_avaliacaogruporesposta = $parametros->preenchimento"
        );

        $sql = $dao->sql_query_file(null, "*", 'efd04_sequencial DESC', implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os preenchimentos anteriores do processo.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return pg_fetch_object($rs)->efd04_avaliacaogruporesposta;
    }

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @return int
     * @throws Exception
     */
    public function persistir(Avaliacao $avaliacao, array $parametros = array())
    {
        $dao = new cl_avaliacaogruporespostaretencaoservicostomados();
        if (!empty($parametros['preenchimento'])) {
            $dao->excluir(null, "efd04_avaliacaogruporesposta = {$parametros['preenchimento']}");
        }

        $preenchimento = $avaliacao->getAvaliacaoGrupo();
        $ano = $parametros['ano'];
        $mes = $parametros['mes'];
        $cgmPrestador = $parametros['z01_numcgm'];
        $cgmContribuinte = $this->getCgm()->getCodigo();

        $where = array(
            "efd04_cgmcontribuinte = {$cgmContribuinte}",
            "efd04_cgmprestador = {$cgmPrestador}",
            "efd04_ano = {$ano}",
            "efd04_mes = {$mes}",
        );

        $sql = $dao->sql_query_file(null, "*", null, implode(' and ', $where));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar prenchimento de retenção.");
        }

        if (pg_num_rows($rs) > 0) {
            throw new Exception("Já existe um preenchimento cadastrado para o contribuinte {$this->getCgm()->getNome()} e para o prestador com cgm {$cgmPrestador}.");
        }

        $dao->efd04_sequencial = null;
        $dao->efd04_cgmprestador = $cgmPrestador;
        $dao->efd04_cgmcontribuinte = "{$cgmContribuinte}";
        $dao->efd04_ano = $ano;
        $dao->efd04_mes = $mes;
        $dao->efd04_avaliacaogruporesposta = $preenchimento;
        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar o processo.");
        }

        return $dao->efd04_avaliacaogruporesposta;
    }

    function buscarCodigoPreenchimentoByCompetenciaCgm($parametros){
        $dao = new cl_avaliacaogruporespostaretencaoservicostomados();

        $where = array(
            "efd04_cgmcontribuinte = {$parametros->cgm}",
            "efd04_cgmprestador = {$parametros->cgmprestador}",
            "efd04_ano = {$parametros->ano}",
            "efd04_mes = {$parametros->mes}",
        );

        $sql = $dao->sql_query_file(null, "*", 'efd04_sequencial DESC', implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os preenchimentos anteriores do processo.");
        }

             
        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return pg_fetch_object($rs)->efd04_avaliacaogruporesposta;
    }
}
