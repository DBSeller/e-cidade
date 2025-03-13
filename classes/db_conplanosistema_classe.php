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
 * Class cl_conplanosistema
 */
class cl_conplanosistema extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('contabilidade.conplanosistema');
    }

    /**
     * @param string $campos
     * @param null $where
     * @param null $outrosComandos
     * @return string
     */
    public function sql_query_vinculo_contas($campos = "*", $where = null, $outrosComandos = null)
    {

        $sql  = " select {$campos} ";
        $sql .= "   from conplanosistema ";
        $sql .= "        join conplanoatributos on conplanoatributos.c120_conplanosistema = conplanosistema.c122_sequencial ";
        $sql .= "        join conplanoinfocomplementar on conplanoinfocomplementar.c121_sequencial = conplanoatributos.c120_infocomplementar ";
        $sql .= "        join conplano on conplano.c60_codcon = conplanoatributos.c120_conplano ";
        $sql .= "                     and conplano.c60_anousu = conplanoatributos.c120_anousu ";

        if (!empty($where) && is_array($where)) {
            $sql .= ' where ' . implode(' and ', $where);
        }

        if (!empty($where) && is_string($where)) {
            $sql .= ' where ' . $where;
        }

        if (!empty($outrosComandos)) {
            $sql .= " " . $outrosComandos;
        }

        return $sql;
    }
}
