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
use cl_avaliacaogruporespostaefdr2020;
use ECidade\Integracao\Sped\Common\Evento\EventoAbstract;
use Exception;
use stdClass;

class R2020 extends EventoAbstract
{
    const AVALIACAO = 3000040;

    private $dao;

    public function __construct()
    {
        $this->dao = new cl_avaliacaogruporespostaefdr2020();
    }

    /**
     * @param stdClass $parametros
     * @return stdClass|null
     * @throws Exception
     */
    public function buscarCodigoPreenchimento(stdClass $parametros)
    {
        if (empty($parametros->preenchimento)) {
            return null;
        }

        $where = array(
            "efd05_avaliacaogruporesposta = $parametros->preenchimento"
        );

        $sql = $this->dao->sql_query_file(null, "*", 'efd05_sequencial DESC', implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o preenchimento.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return pg_fetch_object($rs)->efd05_avaliacaogruporesposta;
    }

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @return int
     */
    public function persistir(Avaliacao $avaliacao, array $parametros = array())
    {
        if (!empty($parametros['preenchimento'])) {
            $this->dao->excluir(null, "efd05_avaliacaogruporesposta = {$parametros['preenchimento']}");
        }

        $preenchimento = $avaliacao->getAvaliacaoGrupo();
        $competencia = $parametros['perApur'];
        $inscricao = $parametros['nrInscEstabPrest'];
        $inscricao = str_replace(array('.', '/', '-'), '', $inscricao);
        $cgm = $this->getCgm()->getCodigo();

        $where = array(
            "efd05_cgm = {$cgm}",
            "efd05_inscricaoprestadora = '{$inscricao}'",
            "efd05_competencia = '{$competencia}'",
        );

        $sql = $this->dao->sql_query_file(null, "*", null, implode(' and ', $where));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar preenchimento de processo.");
        }

        if (pg_num_rows($rs) > 0) {
            throw new Exception("Já existe uma prestação na competência {$competencia} para a inscrição {$inscricao} cadastrado para o contribuinte {$this->getCgm()->getNome()}.");
        }

        $this->dao->efd05_sequencial = null;
        $this->dao->efd05_cgm = $cgm;
        $this->dao->efd05_inscricaoprestadora = $inscricao;
        $this->dao->efd05_competencia = $competencia;
        $this->dao->efd05_avaliacaogruporesposta = $preenchimento;
        $this->dao->efd05_avaliacao = $avaliacao->getCodigo();
        $this->dao->incluir(null);

        if ($this->dao->erro_status == 0) {
            throw new Exception("Erro ao salvar o prestação.");
        }

        return $this->dao->efd05_avaliacaogruporesposta;
    }
}
