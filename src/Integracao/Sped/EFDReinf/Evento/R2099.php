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
use cl_avaliacaogruporespostafechamentoefd;
use ECidade\Integracao\Sped\Common\Avaliacao\AvaliacaoSped;
use ECidade\Integracao\Sped\Common\Evento\EventoAbstract;
use Exception;
use stdClass;

class R2099 extends EventoAbstract
{
    const AVALIACAO = 3000042;

    private $dao;

    public function __construct()
    {
        $this->dao = new cl_avaliacaogruporespostafechamentoefd();
    }

    /**
     * @param stdClass $parametros
     * @return stdClass|null
     * @throws Exception
     */
    public function buscarCodigoPreenchimento(stdClass $parametros)
    {
        $where = array(
            "eso32_cgmcontribuinte = {$this->getCgm()->getCodigo()}",
            "eso32_ano = {$parametros->ano}",
            "eso32_mes = {$parametros->mes}",
        );

        $sql = $this->dao->sql_query_file(null, "*", 'eso32_sequencial DESC', implode(' AND ', $where));
        $rs = db_query($sql);
        
        if (!$rs) {
            throw new Exception("Não foi possível buscar o preenchimento.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return pg_fetch_object($rs)->eso32_avaliacaogruporesposta;
    }

    /**
     * @param Avaliacao $avaliacao
     * @param array $parametros
     * @return int
     */
    public function persistir(Avaliacao $avaliacao, array $parametros = array())
    {
        $preenchimento = $avaliacao->getAvaliacaoGrupo();
        if (empty($parametros['preenchimento'])) {
            $parametros['preenchimento'] = $preenchimento;
        }

        $this->dao->excluir(null, "eso32_avaliacaogruporesposta = {$parametros['preenchimento']}");

        $cgm = $this->getCgm()->getCodigo();
        $ano = $parametros['ano'];
        $mes = $parametros['mes'];

        $this->dao->eso32_sequencial = null;
        $this->dao->eso32_cgmcontribuinte = $cgm;
        $this->dao->eso32_ano = $ano;
        $this->dao->eso32_mes = $mes;
        $this->dao->eso32_avaliacaogruporesposta = $preenchimento;
        $this->dao->incluir(null);

        if ($this->dao->erro_status == 0) {
            throw new Exception("Erro ao salvar o prestação.");
        }

        return $this->dao->eso32_avaliacaogruporesposta;
    }
}