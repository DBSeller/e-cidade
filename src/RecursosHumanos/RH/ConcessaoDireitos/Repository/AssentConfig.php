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

namespace ECidade\RecursosHumanos\RH\ConcessaoDireitos\Repository;

class AssentConfig
{
    public static function verificarsecao($matricula, $rh500_sequencial)
    {
        $rh500_selecao = self::assentconf($rh500_sequencial)[0]['r44_where'];

        $where = [];
        $where[] = "rh01_regist = $matricula";

        if ($rh500_selecao != null) {
            $where[] = $rh500_selecao;
        }
        $sql = "
        select
            DISTINCT rh01_admiss
        from
            rhpessoal
        inner join pessoal.rhpessoalmov on rh02_regist = rh01_regist
        where
        " . implode(" and ", $where);
        if (pg_fetch_all(db_query($sql))) {
            return true;
        } else {
            return false;
        }
    }

    public static function assentconf($rh500_sequencial)
    {
        $sql = "
        select
            assentconf.*,r44_where
        from
            recursoshumanos.assentconf
        left join pessoal.selecao on r44_selec = rh500_selecao
        where
            rh500_sequencial = $rh500_sequencial";

        return pg_fetch_all(db_query($sql));
    }
}
