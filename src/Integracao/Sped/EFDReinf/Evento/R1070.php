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
use cl_avaliacaogruporespostaefdprocesso;
use ECidade\Integracao\Sped\Common\Avaliacao\AvaliacaoSped;
use ECidade\Integracao\Sped\Common\Evento\EventoAbstract;
use Exception;
use stdClass;

class R1070 extends EventoAbstract
{
    const AVALIACAO = 3000037;

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

        $dao = new cl_avaliacaogruporespostaefdprocesso();
        $where = array(
            "efd02_avaliacaogruporesposta = $parametros->preenchimento"
        );

        $sql = $dao->sql_query_file(null, "*", 'efd02_sequencial DESC', implode(' AND ', $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os preenchimentos anteriores do processo.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return pg_fetch_object($rs)->efd02_avaliacaogruporesposta;
    }

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @return int
     * @throws Exception
     */
    public function persistir(Avaliacao $avaliacao, array $parametros = array())
    {
        $dao = new cl_avaliacaogruporespostaefdprocesso();
        if (!empty($parametros['preenchimento'])) {
            $dao->excluir(null, "efd02_avaliacaogruporesposta = {$parametros['preenchimento']}");
        }

        $preenchimento = $avaliacao->getAvaliacaoGrupo();
        $tipo = AvaliacaoSped::getValorRespostaByCodigoPergunta($parametros['tpProc']);
        $numero = $parametros['nrProc'];
        $cgm = $this->getCgm()->getCodigo();

        $where = array(
            "efd02_cgm = {$cgm}",
            "efd02_processo = '{$numero}'",
            "efd02_tipoprocesso = {$tipo}",
        );

        $sql = $dao->sql_query_file(null, "*", null, implode(' and ', $where));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar prenchimento de processo.");
        }

        if (pg_num_rows($rs) > 0) {
            throw new Exception("Já existe um processo {$this->tipos[$tipo]} de número {$numero} cadastrado para o contribuinte {$this->getCgm()->getNome()}.");
        }

        $dao->efd02_sequencial = null;
        $dao->efd02_cgm = $cgm;
        $dao->efd02_processo = "{$numero}";
        $dao->efd02_tipoprocesso = $tipo;
        $dao->efd02_avaliacaogruporesposta = $preenchimento;
        $dao->efd02_avaliacao = $avaliacao->getCodigo();
        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar o processo.");
        }

        return $dao->efd02_avaliacaogruporesposta;
    }
}