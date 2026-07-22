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
 * Class cl_matordemprocessoentrada
 * @property int m57_sequencial
 * @property int m57_matordem
 * @property int m57_processoentrada
 */
class cl_matordemprocessoentrada extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('material.matordemprocessoentrada');
    }


    public function sql_query_empenho($campos = "*", $where = null)
    {
        $sql = " select {$campos} ";
        $sql .= "   from matordemprocessoentrada";
        $sql .= "        inner join matordem     on matordem.m51_codordem = matordemprocessoentrada.m57_matordem ";
        $sql .= "        inner join matordemitem on matordemitem.m52_codordem = matordem.m51_codordem ";
        $sql .= "        inner join empempitem   on empempitem.e62_numemp = matordemitem.m52_numemp ";
        $sql .= "                               and empempitem.e62_sequen = matordemitem.m52_sequen ";
        $sql .= "        inner join empempenho   on empempenho.e60_numemp = empempitem.e62_numemp ";

        if (!empty($where)) {
            $sql .= " where {$where} ";
        }

        return $sql;
    }

    public function sql_query_ordem_entrada($campos = "*", $where = null)
    {
        $sql = " select {$campos} ";
        $sql .= "   from matordemprocessoentrada";
        $sql .= "        inner join matordem         on matordem.m51_codordem = matordemprocessoentrada.m57_matordem ";
        $sql .= "        inner join matordemitem     on matordemitem.m52_codordem = matordem.m51_codordem ";
        $sql .= "        inner join matestoqueitemoc on matestoqueitemoc.m73_codmatordemitem = matordemitem.m52_codlanc ";
        $sql .= "        inner join empempitem       on empempitem.e62_numemp = matordemitem.m52_numemp ";
        $sql .= "                                   and empempitem.e62_sequen = matordemitem.m52_sequen ";
        $sql .= "        inner join empempenho       on empempenho.e60_numemp = empempitem.e62_numemp ";

        if (!empty($where)) {
            $sql .= " where {$where} ";
        }

        return $sql;
    }

    public function sqlOrdemProcessoEntradaNota($campos = "*", $where = null)
    {
        $sql = " select {$campos} ";
        $sql .= "   from matordemprocessoentrada";
        $sql .= "        inner join empnotaord on empnotaord.m72_codordem = matordemprocessoentrada.m57_matordem";

        if (!empty($where)) {
            $sql .= " where {$where} ";
        }

        return $sql;
    }
}