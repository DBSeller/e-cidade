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

namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_esocialconfiguracao;
use Exception;
use Instituicao;
use stdClass;

/**
 * Class ConfiguracaoRepository
 * @package ECidade\RecursosHumanos\ESocial\Repository
 */
class ConfiguracaoRepository
{
    /**
     * @var cl_esocialconfiguracao
     */
    private $dao;

    /**
     * ConfiguracaoRepository constructor.
     */
    public function __construct()
    {
        $this->dao = new cl_esocialconfiguracao();
    }

    /**
     * @param Instituicao $instituicao
     * @return stdClass
     * @throws Exception
     */
    public function getByInstituicao(Instituicao $instituicao)
    {
        $where = "eso25_instit = {$instituicao->getCodigo()}";
        $sql = $this->dao->sql_query_file(null, '*', null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar a configuração para exibir o botão do eSocial. Contate o suporte.');
        }

        $configuracao = new stdClass();
        $configuracao->instituicao = $instituicao->getCodigo();

        if (pg_num_rows($rs) === 0) {
            $configuracao->sequencial = null;
            $configuracao->exibirBotaoESocialParaOsUsuarios = true;
            return $configuracao;
        }

        $resultado = pg_fetch_object($rs);
        $configuracao->sequencial = $resultado->eso25_sequencial;
        $configuracao->exibirBotaoESocialParaOsUsuarios = $resultado->eso25_exibirbotaoesocial === 't';

        return $configuracao;
    }

    /**
     * @param stdClass $configuracao
     * @return stdClass
     * @throws Exception
     */
    public function persist(stdClass $configuracao)
    {
        $this->dao->eso25_sequencial = $configuracao->sequencial;
        $this->dao->eso25_exibirbotaoesocial = $configuracao->exibirBotaoESocialParaOsUsuarios === 'true' ? 't' : 'f';
        $this->dao->eso25_instit = $configuracao->instituicao;

        if (empty($this->dao->eso25_sequencial)) {
            $this->dao->incluir($this->dao->eso25_sequencial);
        } else {
            $this->dao->alterar($this->dao->eso25_sequencial);
        }

        if ($this->dao->erro_status === '0') {
            $mensagem = pg_last_error();

            throw new Exception("Não foi possível salvar os dados da configuração.\n{$mensagem}");
        }

        return $this->getByInstituicao(new Instituicao($configuracao->instituicao));
    }
}
