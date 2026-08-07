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

namespace ECidade\Tributario\Cadastro\Repository;

use cl_ruastipo;
use Exception;
use ECidade\Tributario\Cadastro\Model\RuasTipo;

/**
 * Class RuasTipoRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class RuasTipoRepository
{
    /**
     * @param integer $id
     * @return RuasTipo
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new cl_ruastipo();
        $rs = db_query($dao->sql_query_file($id));

        if (!$rs) {
            throw new Exception("Erro ao buscar tipo de Rua.");
        }

        return RuasTipo::fromState(pg_fetch_array($rs));
    }
}
