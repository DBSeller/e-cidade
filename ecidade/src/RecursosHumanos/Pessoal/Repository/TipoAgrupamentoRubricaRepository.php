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

namespace ECidade\RecursosHumanos\Pessoal\Repository;

use cl_tipoagrupamentorubrica;
use ECidade\RecursosHumanos\Pessoal\Model\TipoAgrupamentoRubrica;
use Exception;

/**
 * Class TipoAgrupamentoRubricaRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 */
class TipoAgrupamentoRubricaRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @param $id
     * @param array $columns
     * @return bool|TipoAgrupamentoRubrica
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_tipoagrupamentorubrica;
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o período da contribuicao sindical.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return TipoAgrupamentoRubrica::fromState($resultado);
    }

    /**
     * @param TipoAgrupamentoRubrica $TipoAgrupamentoRubrica
     * @return TipoAgrupamentoRubrica
     * @throws Exception
     */
    public static function persist(TipoAgrupamentoRubrica $tipoagrupamento)
    {
        $dao = new cl_tipoagrupamentorubrica();
        $dao->rh238_sequencial = $tipoagrupamento->getSequencial();
        $dao->rh238_descricao = $tipoagrupamento->getDescricao();

        if ($tipoagrupamento->getSequencial()) {
            $dao->alterar($tipoagrupamento->getSequencial());
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == '0') {
            throw new Exception("Não foi possível salvar as informações.\nContate o suporte.$dao->erro_msg");
        }

        $tipoagrupamento->setSequencial($dao->rh238_sequencial);

        return $tipoagrupamento;
    }

    /**
     * @param TipoAgrupamentoRubrica|null $TipoAgrupamentoRubrica
     * @throws Exception
     */
    public function delete(TipoAgrupamentoRubrica $tipoagrupamento = null)
    {
        $sequencial = $tipoagrupamento instanceof TipoAgrupamentoRubrica ? $tipoagrupamento->getSequencial() : null;

        $dao = new cl_tipoagrupamentorubrica();
        $dao->excluir($sequencial);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o TipoAgrupamentoRubrica.\nContate o suporte.");
        }
    }

    /**
     * @param array $columns
     * @return TipoAgrupamentoRubrica[]
     * @throws Exception
     */
    public static function findAll($columns = array('*'))
    {
        $dao = new cl_tipoagrupamentorubrica();
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $tiposAgrupamentos = array();

        if (pg_num_rows($rs) === 0) {
            return $tiposAgrupamentos;
        }

        while ($processo = pg_fetch_array($rs)) {
            $tiposAgrupamentos[] = TipoAgrupamentoRubrica::fromState($processo);
        }

        return $tiposAgrupamentos;
    }
}
