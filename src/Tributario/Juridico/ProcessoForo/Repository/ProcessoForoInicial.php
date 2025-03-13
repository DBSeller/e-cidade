<?php
/**
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

namespace ECidade\Tributario\Juridico\ProcessoForo\Repository;

use ECidade\Tributario\Juridico\ProcessoForo\ProcessoForoInicial as Entity;

/**
 * Repository responsável por operações na tabela processoforoinicial.
 *
 * @method static ProcessoForoInicial getInstance()
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class ProcessoForoInicial extends \BaseClassRepository
{
    protected static $oInstance;

    /**
     * Persiste um processo foro inicial no banco de dados.
     *
     * @param Entity $processoForoInicial
     *
     * @return Entity
     *
     * @throws \Exception
     */
    public function persist(Entity $processoForoInicial)
    {
        $dao = new \cl_processoforoinicial();

        $sequencial = $processoForoInicial->getSequencial();

        $dao->v71_id_usuario = $processoForoInicial->getUsuario();
        $dao->v71_inicial = $processoForoInicial->getInicial();
        $dao->v71_processoforo = $processoForoInicial->getProcessoForo();

        $data = $processoForoInicial->getData();
        if (!empty($data)) {
            $dao->v71_data = $data->format('Y-m-d');
        }

        $dao->v71_anulado = $processoForoInicial->isAnulado() ? 'true' : 'false';

        if (!empty($sequencial)) {
            $dao->v71_sequencial = $sequencial;
            $dao->alterar($sequencial);
        } else {
            $dao->incluir(null);
            $processoForoInicial->setSequencial($dao->v71_sequencial);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $processoForoInicial;
    }

    public function find($where, $campos = "*")
    {
        $dao = new \cl_processoforoinicial();

        $result = $dao->sql_record($dao->sql_query(null, $campos, null, $where));

        if ($dao->erro_status != null) {
            throw new \Exception($dao->erro_msg);
        }

        return \db_utils::getCollectionByRecord($result);
    }
}
