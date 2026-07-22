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

/**
 * Classe responsável por validações de Sql
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
abstract class DBSqlValidador
{
    /**
     * Validamos se o sql altera dados ou estrutura do banco
     * @param $sql
     * @return bool
     */
    public static function sqlAlteraDadosOuEstrutura($sql)
    {
        $commands = array(
          "drop.+?table",
          "alter.+?table",
          "create.+?table",
          "truncate",
          "insert.+?into",
          "update.+?set",
          "delete.+?from",
          "copy.+?from"
        );

        $commands = implode("|", $commands);

        $matches = preg_match_all("/$commands/i", $sql,$result);

        if (empty($matches)) {
            return false;
        }

        return true;
    }
}