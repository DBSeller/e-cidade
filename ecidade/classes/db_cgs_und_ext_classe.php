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

//MODULO: saude
//CLASSE DA ENTIDADE cgs_und

class cl_cgs_und_ext extends cl_cgs_und
{
    function sql_query_ext($z01_i_cgsund = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $sql .= implode(', ', explode("#", $campos));
        } else {
            $sql .= $campos;
        }
        $sql .= " from cgs_und ";
        $sql .= "  left join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
        $sql .= " inner join cgs               on  cgs.z01_i_numcgs = cgs_und.z01_i_cgsund";
        $sql .= "  left join familia           on  familia.sd33_i_codigo = familiamicroarea.sd35_i_familia";
        $sql .= "  left join microarea         on  microarea.sd34_i_codigo = familiamicroarea.sd35_i_microarea";
        $sql .= "  left join cgs_cartaosus     on  s115_i_cgs = cgs_und.z01_i_cgsund and s115_c_tipo='D'";
        $sql .= "  left join cgs_und_ext on cgs_und_ext.z01_i_cgsund = cgs_und.z01_i_cgsund";
        //plugin ESF operation#0 - adicionando novos left join com plugins.cgs_und_esf na query
        $sql2 = "";
        if ($dbwhere == "") {
            if ($z01_i_cgsund != null) {
                $sql2 .= " where cgs_und.z01_i_cgsund = $z01_i_cgsund ";
            }
        } else {
            if ($dbwhere != "") {
                $sql2 = " where $dbwhere";
            }
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $ordem = implode(', ', explode("#", $ordem));
            $sql .= " order by {$ordem}";
        }

        return $sql;
    }

    function sql_query_cgs_profissional(
      $z01_i_cgsund = null,
      $sd04_i_medico,
      $sd04_i_unidade,
      $campos = "*",
      $ordem = null,
      $dbwhere = ""
    ) {

        $sql = "select ";
        if ($campos != "*") {
            $sql .= implode(', ', explode("#", $campos));
        } else {
            $sql .= $campos;
        }
        $sql .= " from cgs_und ";
        $sql .= "      left join familiamicroarea  on  familiamicroarea.sd35_i_codigo = cgs_und.z01_i_familiamicroarea";
        $sql .= "      inner join cgs  on  cgs.z01_i_numcgs = cgs_und.z01_i_cgsund";
        $sql .= "      left join familia  on  familia.sd33_i_codigo = familiamicroarea.sd35_i_familia";
        $sql .= "      left join microarea  on  microarea.sd34_i_codigo = familiamicroarea.sd35_i_microarea";
        $sql .= "      left join cgs_cartaosus  on  s115_i_cgs = cgs_und.z01_i_cgsund and s115_c_tipo='D' ";
        $sql .= "      left join cgs_und_ext on cgs_und_ext.z01_i_cgsund = cgs_und.z01_i_cgsund";
        $sql .= "      inner join prontuarios on prontuarios.sd24_i_numcgs = cgs_und.z01_i_cgsund";
        $sql .= "      inner join unidademedicos on unidademedicos.sd04_i_unidade = prontuarios.sd24_i_unidade ";
        //plugin ESF operation#0 - adicionando novos left join com plugins.cgs_und_esf na query
        $sql2 = " where sd04_i_medico = $sd04_i_medico and sd04_i_unidade = $sd04_i_unidade ";
        if ($dbwhere == "") {
            if ($z01_i_cgsund != null) {
                $sql2 .= " and cgs_und.z01_i_cgsund = $z01_i_cgsund ";
            }
        } else {
            if ($dbwhere != "") {
                $sql2 .= " and $dbwhere";
            }
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $ordem = implode(', ', explode("#", $ordem));
            $sql .= " order by {$ordem}";
        }

        return $sql;
    }


    function sql_query_prontuarios($z01_i_cgsund = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = explode("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from cgs_und ";
        $sql .= "      inner join prontuarios on prontuarios.sd24_i_numcgs = cgs_und.z01_i_cgsund";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($z01_i_cgsund != null) {
                $sql2 .= " where cgs_und.z01_i_cgsund = $z01_i_cgsund ";
            }
        } else {
            if ($dbwhere != "") {
                $sql2 .= " where $dbwhere";
            }
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }

        return $sql;
    }

}
