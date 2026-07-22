<?php
/**
 *  E-cidade Software Publico para Gestao Municipal
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

class cl_infocomplementarvalor extends DAOBasica
{
    public function __construct()
    {
        parent::__construct("contabilidade.infocomplementarvalor");
    }

    public function sql_query_infocomplementar_po_by_lancamento($codigoInstituicao)
    {
        $sql = " SELECT codtrib::varchar as infocomplementar_valor FROM db_config WHERE codigo = {$codigoInstituicao}; ";
        return $sql;
    }

    public function sql_query_consulta_POs()
    {
        $sql = " SELECT distinct codtrib::varchar as codtrib, nomeinst as descricao FROM db_config ";
        return $sql;
    }

    public function sql_query_infocomplementar_fp_by_reduzido($codigoreduzido, $ano, $instituicoes)
    {
        $sql = " SELECT c60_naturezasaldo as infocomplementar_valor ";
        $sql .= " FROM conplanoreduz                                 ";
        $sql .= " INNER JOIN conplano ON c61_codcon = c60_codcon     ";
        $sql .= "    AND c61_anousu = c60_anousu                     ";
        $sql .= " WHERE c61_reduz = {$codigoreduzido}                ";
        $sql .= "    AND c61_anousu = {$ano}                         ";
        $sql .= "    AND c61_instit in ({$instituicoes});            ";

        return $sql;
    }

    public function sql_query_consulta_FPs()
    {
        $sql = " SELECT distinct c60_naturezasaldo as c60_naturezasaldo, ";
        $sql .= " 'Superбvit Financeiro'::varchar as descricao ";
        $sql .= " from conplano ";

        return $sql;
    }

    public function sql_query_infocomplementar_fs_by_lancamento($codigoLancamento)
    {
        $sql = " SELECT (CASE WHEN c75_codlan IS NOT NULL THEN lpad(dotemp.o58_funcao, 2, '0')::varchar||lpad(dotemp.o58_subfuncao, 3, '0')::varchar ";
        $sql .= "     ELSE lpad(dotlan.o58_funcao, 2, '0')::varchar||lpad(dotlan.o58_subfuncao, 3, '0')::varchar END) AS infocomplementar_valor       ";
        $sql .= " FROM conlancam                                                                                                                      ";
        $sql .= " INNER JOIN conlancamdoc ON c71_codlan = c70_codlan                                                                                  ";
        $sql .= " INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc                                                                                    ";
        $sql .= " LEFT JOIN conlancamemp ON c75_codlan = c70_codlan                                                                                   ";
        $sql .= " LEFT JOIN empempenho ON c75_numemp = e60_numemp                                                                                     ";
        $sql .= " LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot                                                                       ";
        $sql .= "     AND e60_anousu = dotemp.o58_anousu                                                                                              ";
        $sql .= " LEFT JOIN conlancamdot ON c73_codlan = c70_codlan                                                                                   ";
        $sql .= " LEFT JOIN orcdotacao dotlan ON c73_coddot = dotlan.o58_coddot                                                                       ";
        $sql .= "     AND c73_anousu = dotlan.o58_anousu                                                                                              ";
        $sql .= " WHERE c70_Codlan = {$codigoLancamento};                                                                                             ";

        return $sql;
    }

    public function sql_query_consulta_FSs()
    {
        $sql = " select distinct (lpad(o58_funcao, 2, '0')||lpad(o58_subfuncao, 3 , '0'))::varchar as Funcao_Subfuncao, '2 Primeiros digitos funзгo / 3 ъltimos digitos subfunзгo'::varchar as descricao from orcdotacao ";
        return $sql;
    }

    public function sql_query_infocomplementar_nr_by_lancamento($codigoLancamento)
    {
        $sql = " SELECT orcfontes.o57_fonte AS infocomplementar_valor,            ";
        $sql .= "        orcfontes.o57_descr AS descricao_nr                       ";
        $sql .= " FROM contabilidade.conlancam                                     ";
        $sql .= " INNER JOIN contabilidade.conlancamdoc ON c71_codlan = c70_codlan ";
        $sql .= " INNER JOIN contabilidade.conhistdoc ON c53_coddoc = c71_coddoc   ";
        $sql .= " LEFT JOIN contabilidade.conlancamrec ON c74_codlan = c70_codlan  ";
        $sql .= " LEFT JOIN orcamento.orcreceita ON c74_codrec = o70_codrec        ";
        $sql .= "     AND c74_anousu = o70_anousu                                  ";
        $sql .= " LEFT JOIN orcamento.orcfontes ON o57_codfon = o70_Codfon         ";
        $sql .= "     AND o57_anousu = o70_anousu                                  ";
        $sql .= " WHERE c70_Codlan = {$codigoLancamento};                          ";

        return $sql;
    }

    public function sql_query_consulta_NRs()
    {
        $sql = 'select distinct o57_fonte as infocomplementar_valor, o57_descr as descricao from orcfontes';
        return $sql;
    }

    public function sql_query_infocomplementar_nd_by_lancamento($codigoLancamento)
    {
        $sql = " SELECT (CASE WHEN c75_codlan IS NOT NULL THEN eleemp.o56_elemento::varchar ";
        $sql .= "     ELSE eledot.o56_elemento::varchar END) AS infocomplementar_valor,      ";
        $sql .= "  (CASE WHEN c75_codlan IS NOT NULL THEN eleemp.o56_descr::varchar          ";
        $sql .= "     ELSE eledot.o56_descr::varchar END) AS descricao_nd                    ";
        $sql .= " FROM conlancam                                                             ";
        $sql .= " INNER JOIN conlancamdoc ON c71_codlan = c70_codlan                         ";
        $sql .= " INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc                           ";
        $sql .= " LEFT JOIN conlancamemp ON c75_codlan = c70_codlan                          ";
        $sql .= " LEFT JOIN empempenho ON c75_numemp = e60_numemp                            ";
        $sql .= " LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot              ";
        $sql .= "     AND e60_anousu = dotemp.o58_anousu                                     ";
        $sql .= " LEFT JOIN orcelemento eleemp ON dotemp.o58_codele = eleemp.o56_codele      ";
        $sql .= "     AND dotemp.o58_anousu = eleemp.o56_anousu                              ";
        $sql .= " LEFT JOIN conlancamdot ON c73_codlan = c70_codlan                          ";
        $sql .= " LEFT JOIN orcdotacao dotlan ON c73_coddot = dotlan.o58_coddot              ";
        $sql .= "     AND c73_anousu = dotlan.o58_anousu                                     ";
        $sql .= " LEFT JOIN orcelemento eledot ON dotlan.o58_codele = eledot.o56_codele      ";
        $sql .= "     AND dotlan.o58_anousu = eledot.o56_anousu                              ";
        $sql .= " WHERE c70_Codlan = {$codigoLancamento};                                    ";

        return $sql;
    }

    public function sql_query_consulta_NDs()
    {
        $sql = 'select distinct o56_elemento as infocomplementar_valor, o56_descr as descricao from orcelemento';
        return $sql;
    }

    public function sql_query_infocomplementar_fr_by_lancamento($codigoLancamento, $codigoreduzido, $ano)
    {
        if (empty($codigoLancamento)) {
            $sql = " SELECT lpad(c61_codigo::varchar, 4, '0') AS infocomplementar_valor FROM conplanoreduz WHERE c61_reduz = {$codigoreduzido} AND c61_anousu = {$ano} ";

            return $sql;
        }

        $sql = " SELECT (CASE WHEN c75_codlan IS NOT NULL AND c53_tipo in(30, 31) THEN lpad(c61_codigo::varchar, 4, '0')                      ";
        $sql .= "              WHEN c75_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN lpad(dotemp.o58_codigo::varchar, 4, '0')           ";
        $sql .= "              WHEN c73_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN lpad(dotlan.o58_codigo::varchar, 4, '0')           ";
        $sql .= "              WHEN c74_codrec IS NOT NULL and dotrec.o58_codigo is not null THEN lpad(dotrec.o58_codigo::varchar, 4, '0')     ";
        $sql .= "              WHEN c74_codrec IS NOT NULL THEN lpad(o70_codigo::varchar, 4, '0')                                              ";
        $sql .= "              WHEN recursopagdebito.c61_reduz IS NOT NULL THEN lpad(c61_codigo::varchar, 4, '0')                              ";
        $sql .= "              ELSE (SELECT lpad(c61_codigo::varchar, 4, '0')                                                                  ";
        $sql .= "                    FROM conplanoreduz                                                                                        ";
        $sql .= "                    WHERE c61_reduz = {$codigoreduzido}                                                                       ";
        $sql .= "                    AND c61_anousu = {$ano})                                                                                  ";
        $sql .= "         END) AS infocomplementar_valor                                                                                       ";
        $sql .= " FROM conlancam                                                                                                               ";
        $sql .= "       INNER JOIN conlancamdoc ON c71_codlan = c70_codlan                                                                     ";
        $sql .= "       INNER JOIN conhistdoc   ON c53_coddoc = c71_coddoc                                                                     ";
        $sql .= "       LEFT JOIN conlancamemp  ON c75_codlan = c70_codlan                                                                     ";
        $sql .= "       LEFT JOIN empempenho empemp1   ON c75_numemp = empemp1.e60_numemp                                                      ";
        $sql .= "       LEFT JOIN orcdotacao dotemp ON empemp1.e60_coddot = dotemp.o58_coddot                                                  ";
        $sql .= "                                   AND empemp1.e60_anousu = dotemp.o58_anousu                                                 ";
        $sql .= "       LEFT JOIN conlancamdot ON c73_codlan = c70_codlan                                                                      ";
        $sql .= "       LEFT JOIN orcdotacao dotlan ON c73_coddot = dotlan.o58_coddot                                                          ";
        $sql .= "                                   AND c73_anousu = dotlan.o58_anousu                                                         ";
        $sql .= "       LEFT JOIN conlancamrec ON c74_codlan = c70_codlan                                                                      ";
        $sql .= "       LEFT JOIN orcreceita ON c74_codrec = o70_codrec                                                                        ";
        $sql .= "                            AND c74_anousu = o70_anousu                                                                       ";
        $sql .= "       LEFT JOIN conlancampag ON c82_codlan = c70_codlan                                                                      ";
        $sql .= "       LEFT JOIN conplanoreduz AS recursopagdebito ON c82_reduz = recursopagdebito.c61_reduz                                  ";
        $sql .= "                                                   AND c82_anousu = recursopagdebito.c61_anousu                               ";
        $sql .= "       LEFT JOIN conlancamcorrente conlancorr1 ON conlancorr1.c86_conlancam =  c70_codlan                                     ";
        $sql .= "       LEFT JOIN corgrupocorrente corgrpcor1 ON corgrpcor1.k105_data = conlancorr1.c86_data                                  ";
        $sql .= "                                              AND corgrpcor1.k105_autent = conlancorr1.c86_autent                             ";
        $sql .= "                                              AND corgrpcor1.k105_id = conlancorr1.c86_id                                     ";
        $sql .= "                                              AND corgrpcor1.k105_corgrupotipo = 3                                            ";
        $sql .= "       LEFT JOIN corgrupocorrente corgrpcor2 ON corgrpcor2.k105_corgrupo = corgrpcor1.k105_corgrupo                           ";
        $sql .= "                                             AND corgrpcor2.k105_corgrupotipo = 1                                             ";
        $sql .= "       LEFT JOIN coremp ON  k12_id     = corgrpcor2.k105_id                                                                   ";
        $sql .= "                        AND k12_data   = corgrpcor2.k105_data                                                                 ";
        $sql .= "                        AND k12_autent = corgrpcor2.k105_autent                                                               ";
        $sql .= "       LEFT JOIN empempenho empemp2 ON  k12_empen = empemp2.e60_numemp                                                        ";
        $sql .= "       LEFT JOIN orcdotacao dotrec  ON empemp2.e60_coddot = dotrec.o58_coddot                                                 ";
        $sql .= "                                    AND empemp2.e60_anousu = dotrec.o58_anousu                                                
        ";
        $sql .= " WHERE c70_Codlan = {$codigoLancamento};                                                                                      ";

        return $sql;
    }

    public function sql_query_consulta_FRs()
    {
        $sql = " SELECT lpad(o15_codigo::varchar, 4, '0')::varchar AS o15_codigo, o15_descr as descricao FROM orctiporec ";
        return $sql;
    }

    public function sql_query_infocomplementar_dc_by_estrutural($codigoConta, $ano)
    {
        $sql = " select (CASE WHEN c60_codsis = 9 THEN 0 ELSE 1 END) AS infocomplementar_valor  from conplano where c60_codcon = {$codigoConta} and  c60_anousu = {$ano} ";

        return $sql;
    }

    public function sql_query_consulta_DCs()
    {
        $sql = " select distinct c52_codsis AS infocomplementar_valor, c52_descr as descricao from consistema ";

        return $sql;
    }

    /**
     * @param string $campos
     * @param null $where
     * @param null $order
     *
     * @return string
     */
    public function sql_query_lancamento($campos = '*', $where = null, $order = null)
    {
        $sql = "select {$campos} ";
        $sql .= "  from infocomplementarvalor ";
        $sql .= "       inner join conplanoatributolancamentos on c123_conplanoatributolancamentos = c124_sequencial ";
        $sql .= "       inner join conlancamdoc  on c124_lancamento = c71_codlan ";
        $sql .= "       inner join conhistdoc    on c71_coddoc = c53_coddoc  ";
        $sql .= "       inner join conplanoreduz on c123_reduzido = c61_reduz ";
        $sql .= "                               and extract(year from c124_data)::int = c61_anousu ";
        $sql .= "       inner join conplano      on c61_codcon = c60_codcon ";
        $sql .= "                               and c61_anousu = c60_anousu ";

        if (!empty($where)) {
            $sql .= "where {$where}";
        }
        if (!empty($order)) {
            $sql .= "where {$order}";
        }

        return $sql;
    }
}
