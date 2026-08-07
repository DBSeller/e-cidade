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

namespace ECidade\Patrimonial\Patrimonio\Bem\Repository;

use ECidade\Patrimonial\Patrimonio\Bem\Service\BemService;
use ECidade\Patrimonial\Patrimonio\Bem\Model\Bem;
use ECidade\Patrimonial\Patrimonio\Bem\Model\BemPlaca;

class BemRepository
{
    /**
     * @var Object
     */
    private $dao;

    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    public function busca($id, $colunas = array('*'))
    {
        $sql = $this->dao->sql_query_file($id, implode(', ', $colunas));
        $rs = db_query($sql);
        
        if (!$rs) {
            throw new Exception("Não foi possível buscar o Bem.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);
        $bem = Bem::fromState($resultado);
        
        return $bem->withBemPlacas();
    }
}
