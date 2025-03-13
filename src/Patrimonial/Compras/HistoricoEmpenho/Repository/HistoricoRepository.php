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

namespace ECidade\Patrimonial\Compras\HistoricoEmpenho\Repository;

use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model\Autorizacao;
use ECidade\Patrimonial\Compras\HistoricoEmpenho\Model\Historico;
use mysql_xdevapi\Exception;

/**
 * Class AutorizacaoEmpenhoRepository
 * @package ECidade\Patrimonial\Compras\Repository
 */
class HistoricoRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @var Object
     */
    private $dao;

    /**
     * @param  $numpre
     * @param string $operador
     * @return $this
     */
    public function scopeCodigoAutorizacao($codigoAutorizacao)
    {
        $this->scopes['e57_autori'] = "e57_autori = {$codigoAutorizacao}";
        return $this;
    }

    /**
     * AutorizacaoRepository constructor.
     * @param $dao \cl_emphist
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|Historico
     * @throws \Exception
     */
    public function find($id, $columns = array('*'))
    {
        $sql = $this->dao->sql_query_file($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new \Exception("");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Historico::fromState($resultado);
    }

    /**
     * @param \cl_empauthist $daoHistoricoAutorizacao
     * @param Autorizacao $autorizacao
     * @return Historico
     * @throws \Exception
     */
    public function getHistoricoPorAutorizacao($daoHistoricoAutorizacao, Autorizacao $autorizacao)
    {
        $sql = $daoHistoricoAutorizacao->sql_query_file($autorizacao->getCodigoAutorizacao(), "e57_codhist as codigo");
        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) === 0) {
            return null;
        }

        $codigoHistorico = pg_fetch_object($rs);

        return $this->find($codigoHistorico->codigo);
    }
}
