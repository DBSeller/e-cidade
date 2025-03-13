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

namespace ECidade\Patrimonial\Compras\TipoPrestacaoEmpenho\Repository;

use ECidade\Patrimonial\Compras\TipoPrestacaoEmpenho\Model\TipoPrestacao;
use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model\Autorizacao;

/**
 * Class AutorizacaoEmpenhoRepository
 * @package ECidade\Patrimonial\Compras\Repository
 */
class TipoPrestacaoRepository
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
     * TipoPrestacaoRepository constructor.
     * @param $dao \cl_empprestatip
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|TipoPrestacao
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

        return TipoPrestacao::fromState($resultado);
    }

    /**
     * @param \cl_empautpresta $daoTipoPrestacaoAutorizacao
     * @param Autorizacao $autorizacao
     * @return TipoPrestacao
     * @throws \Exception
     */
    public function getTipoPrestacaoPorAutorizacao($daoTipoPrestacaoAutorizacao, Autorizacao $autorizacao)
    {
        $sql = $daoTipoPrestacaoAutorizacao->sql_query_file(
            null,
            "e58_tipo as tipopresta",
            null,
            " e58_autori = {$autorizacao->getCodigoAutorizacao()} "
        );

        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) === 0) {
            return null;
        }

        $tipoPrestacao = pg_fetch_object($rs);

        return $this->find((int) $tipoPrestacao->tipopresta);
    }
}
