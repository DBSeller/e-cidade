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

namespace ECidade\Patrimonial\Compras\ProcessoAdministrativoEmpenho\Repository;

use ECidade\Patrimonial\Compras\ProcessoAdministrativoEmpenho\Model\ProcessoAdministrativo;
use ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model\Autorizacao;

/**
 * Class ProcessoAdministrativoRepository
 * @package ECidade\Patrimonial\Compras\Repository
 */
class ProcessoAdministrativoRepository
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
     * ProcessoAdministrativoRepository constructor.
     * @param $dao \cl_empautorizaprocesso
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ProcessoAdministrativo
     * @throws \Exception
     */
    public function find($id, $columns = array('*'))
    {
        $sql = $this->dao->sql_query_file(null, implode(', ', $columns), null, "e150_empautoriza = {$id}");
        $rs = db_query($sql);

        if (!$rs || pg_num_rows($rs) === 0) {
            return null;
        }

        $resultado = pg_fetch_array($rs);

        return ProcessoAdministrativo::fromState($resultado);
    }

    public function getProcessoAdministrativoPorAutorizacao(Autorizacao $autorizacao)
    {
        return $this->find($autorizacao->getCodigoAutorizacao());
    }
}
