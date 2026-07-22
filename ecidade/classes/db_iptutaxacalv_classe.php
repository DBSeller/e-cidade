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

/*
 * isscalc
 * issvar
 * vistorianumpre
 */

class cl_iptutaxacalv extends \DAOBasica
{
    public function __construct()
    {
        parent::__construct("cadastro.iptutaxacalv");
    }

    function sql_queryValoresCalculoIptu($iAnoCalculo)
    {
        $sSql = "select ano_calculo,                                                                             ";
        $sSql .= "       codigo_receita,                                                                          ";
        $sSql .= "       descricao_receita,                                                                       ";
        $sSql .= "       coalesce(round(sum(valor_calculado), 2)  , 0.00) as valor_calculado,                     ";
        $sSql .= "       coalesce(round(sum(valor_isento)   , 2)  , 0.00) as valor_isento,                        ";
        $sSql .= "       coalesce(round(sum(valor_cancelado), 2)  , 0.00) as valor_cancelado,                     ";
        $sSql .= "       coalesce(round(sum(valor_compensado), 2) , 0.00) as valor_compensado,                    ";
        $sSql .= "       coalesce(round(sum(valor_pago)     , 2)  , 0.00) as valor_pago,                          ";
        $sSql .= "       coalesce(round(sum(valor_a_pagar)  , 2)  , 0.00) as valor_a_pagar,                       ";
        $sSql .= "       coalesce(round(sum(case when valor_importado_arreold <> null ";
        $sSql .= "                               then valor_importado_arreold ";
        $sSql .= "                               else valor_importado_outros end) , 2), 0.00) AS valor_importado, ";
        $sSql .= "       (SELECT Count(j151_matric)
					  FROM   iptutaxacalv
				       INNER JOIN iptutaxanump
    			               ON j152_iptutaxanump = j151_codigo
				       INNER JOIN iptucadtaxaexe
				               ON j151_iptucadtaxaexe = j08_iptucadtaxaexe
				       WHERE j08_anousu = ano_calculo
		 		       AND j08_tabrec = codigo_receita
				       AND j152_valor > 0) as quantidade                                                      ";
        $sSql .= "  from (                                                                                        ";
        $sSql .= "       select tabrec.k02_codigo                                           as codigo_receita,    ";
        $sSql .= "              iptucadtaxaexe.j08_anousu                                   as ano_calculo,       ";
        $sSql .= "              tabrec.k02_descr                                            as descricao_receita, ";
        $sSql .= "              sum(case when j152_valor > 0 then j152_valor      else 0 end) as valor_calculado, ";
        $sSql .= "              sum(CASE WHEN j152_valor < 0 and (1 = (                                           ";
        $sSql .= "                            select 1                                                            ";
        $sSql .= "                            from iptucalhconf                                                   ";
        $sSql .= "                                 inner join iptuisen on                                         ";
        $sSql .= "                                              iptutaxanump.j151_matric = iptuisen.j46_matric    ";
        $sSql .= "                                              and iptucadtaxaexe.j08_anousu = {$iAnoCalculo}    ";
        $sSql .= "                                 inner join isenexe  on isenexe.j47_codigo = iptuisen.j46_codigo";
        $sSql .= "                                 inner join tipoisen on tipoisen.j45_tipo = iptuisen.j46_tipo   ";
        $sSql .= "                            where                                                               ";
        $sSql .= "                                iptucalhconf.j89_codhis  = iptutaxacalv.j152_codhis             ";
        $sSql .= "                                and isenexe.j47_anousu = iptucadtaxaexe.j08_anousu              ";
        $sSql .= "                            limit 1)                                                            ";
        $sSql .= "                       ) THEN j152_valor* -1 ELSE 0 END) AS valor_isento,                       ";
        $sSql .= "              (select sum(arrecant.k00_valor)                                                   ";
        $sSql .= "                 from arrecant                                                                  ";
        $sSql .= "                inner join cancdebitosreg on k21_numpre = k00_numpre                            ";
        $sSql .= "                                         and k21_numpar = k00_numpar                            ";
        $sSql .= "                                         and k21_receit = k00_receit                            ";
        $sSql .= "                inner join cancdebitosprocreg on k24_cancdebitosreg = k21_sequencia             ";
        $sSql .= "                where arrecant.k00_numpre = iptutaxanump.j151_numpre                            ";
        $sSql .= "                  and arrecant.k00_receit = tabrec.k02_codigo)            as valor_cancelado,   ";
        $sSql .= "      (select sum(valor) from (";
        $sSql .= "        select sum(arrecant.k00_valor) as valor from arrecant";
        $sSql .= "          where exists(";
        $sSql .= "            select 1 FROM abatimentoutilizacaodestino";
        $sSql .= "              inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao";
        $sSql .= "              inner join abatimento on k125_sequencial = k157_abatimento";
        $sSql .= "            where arrecant.k00_numpre = k170_numpre";
        $sSql .= "              and arrecant.k00_numpar = k170_numpar";
        $sSql .= "              and arrecant.k00_receit = k170_receit";
        $sSql .= "              and k125_tipoabatimento = " . Abatimento::TIPO_CREDITO;
        $sSql .= "            limit 1";
        $sSql .= "          )";
        $sSql .= "          and arrecant.k00_numpre = iptutaxanump.j151_numpre";
        $sSql .= "          and arrecant.k00_receit = tabrec.k02_codigo";
        $sSql .= "        union all";
        $sSql .= "        select sum(abatimentoarreckey.k128_valorabatido) AS valor from arrecad";
        $sSql .= "          inner join arreckey on arrecad.k00_numpre = arreckey.k00_numpre";
        $sSql .= "                             and arrecad.k00_numpar = arreckey.k00_numpar";
        $sSql .= "                             and arrecad.k00_receit = arreckey.k00_receit";
        $sSql .= "          inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial";
        $sSql .= "          inner join abatimento on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial";
        $sSql .= "        where arrecad.k00_numpre = iptutaxanump.j151_numpre";
        $sSql .= "          and arrecad.k00_receit = tabrec.k02_codigo";
        $sSql .= "          and abatimento.k125_tipoabatimento = " . Abatimento::TIPO_COMPENSACAO;
        $sSql .= "        ) as valor) as valor_compensado,";
        $sSql .= "              (select sum(valor) from  ";
        $sSql .= "               (select (arrecant.k00_valor) as valor ";
        $sSql .= "                  from arrecant ";
        $sSql .= "                 where exists(select 1 ";
        $sSql .= "                                from arrepaga ";
        $sSql .= "                               where k00_numpre = arrecant.k00_numpre ";
        $sSql .= "                                 and k00_numpar = arrecant.k00_numpar ";
        $sSql .= "                                 and k00_receit = arrecant.k00_receit)";
        $sSql .= "                  and arrecant.k00_numpre = iptutaxanump.j151_numpre ";
        $sSql .= "                  and arrecant.k00_receit = tabrec.k02_codigo";
        $sSql .= "                  and not exists(select 1 ";
        $sSql .= "                    from abatimentoutilizacaodestino";
        $sSql .= "                      inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao";
        $sSql .= "                      inner join abatimento on k125_sequencial = k157_abatimento";
        $sSql .= "                    where";
        $sSql .= "                      k170_numpre = arrecant.k00_numpre and";
        $sSql .= "                      k170_numpar = arrecant.k00_numpar and";
        $sSql .= "                      k170_receit = arrecant.k00_receit and";
        $sSql .= "                      k125_tipoabatimento = " . Abatimento::TIPO_CREDITO;
        $sSql .= "                    limit 1";
        $sSql .= "                  )";
        $sSql .= "                union all ";
        $sSql .= "               select sum(abatimentoarreckey.k128_valorabatido) as valor ";
        $sSql .= "                 from arrecad ";
        $sSql .= "                      inner join arreckey on arrecad.k00_numpre = arreckey.k00_numpre ";
        $sSql .= "                                         and arrecad.k00_numpar = arreckey.k00_numpar ";
        $sSql .= "                                         and arrecad.k00_receit = arreckey.k00_receit ";
        $sSql .= "                      inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial ";
        $sSql .= "                      inner join abatimento on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial ";
        $sSql .= "                where arrecad.k00_numpre = iptutaxanump.j151_numpre ";
        $sSql .= "                  and arrecad.k00_receit = tabrec.k02_codigo ";
        $sSql .= "                  and abatimento.k125_tipoabatimento = " . Abatimento::TIPO_PAGAMENTO_PARCIAL;
        $sSql .= "                union all ";
        $sSql .= "               select sum(abatimentoarreckey.k128_valorabatido) as valor ";
        $sSql .= "                 from arrecant ";
        $sSql .= "                      inner join arreckey on arrecant.k00_numpre = arreckey.k00_numpre ";
        $sSql .= "                                         and arrecant.k00_numpar = arreckey.k00_numpar ";
        $sSql .= "                                         and arrecant.k00_receit = arreckey.k00_receit ";
        $sSql .= "                      inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial ";
        $sSql .= "                      inner join abatimento on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial ";
        $sSql .= "                where arrecant.k00_numpre = iptutaxanump.j151_numpre ";
        $sSql .= "                  and arrecant.k00_receit = tabrec.k02_codigo ";
        $sSql .= "                  and abatimento.k125_tipoabatimento = " . Abatimento::TIPO_PAGAMENTO_PARCIAL;
        $sSql .= "              ) as y) AS valor_pago,                                  									     ";
        $sSql .= "              (select sum(arrecad.k00_valor)                                      							 ";
        $sSql .= "                 from arrecad                                                     							 ";
        $sSql .= "                where arrecad.k00_numpre = iptutaxanump.j151_numpre               							 ";
        $sSql .= "                  and arrecad.k00_receit = tabrec.k02_codigo) as valor_a_pagar,   							 ";

        $sSql .= "               (select sum(k00_valor) as valor                                              					 ";
        $sSql .= "                 from arreold                                                     							 ";
        $sSql .= "                where k00_numpre in (select distinct k10_numpre                   							 ";
        $sSql .= "                                       from divold                                							 ";
        $sSql .= "                                      where k10_numpre = iptutaxanump.j151_numpre)							 ";
        $sSql .= "                  and k00_receit =  tabrec.k02_codigo                             							 ";
        $sSql .= "                group by k00_receit) as valor_importado_arreold,                           					 ";

        $sSql .= "              ( ";
        $sSql .= "               SELECT sum(x.dv05_vlrhis) as valor ";
        $sSql .= "                 FROM (SELECT DISTINCT dv13_numpre, ";
        $sSql .= "                              dv05_vlrhis ";
        $sSql .= "                         FROM diverimportaold ";
        $sSql .= "                              INNER JOIN diversos ON dv05_coddiver = dv13_diversos ";
        $sSql .= "                        WHERE dv13_numpre = iptutaxanump.j151_numpre ";
        $sSql .= "                          AND dv13_receita = tabrec.k02_codigo ";
        $sSql .= "                        GROUP BY dv13_numpre, dv13_diversos, dv05_vlrhis) AS x ";
        $sSql .= " ";
        $sSql .= "                UNION ALL                           					             ";
        $sSql .= " ";
        $sSql .= "               select sum(k00_valor) as valor                                              					 ";
        $sSql .= "                 from arreold                                                     							 ";
        $sSql .= "                where k00_numpre in (select distinct q05_numpre                   							 ";
        $sSql .= "                                       from issvar                                							 ";
        $sSql .= "                                      where q05_numpre = iptutaxanump.j151_numpre)							 ";
        $sSql .= "                  and k00_receit =  tabrec.k02_codigo                             							 ";
        $sSql .= "                group by k00_receit                           					 ";
        $sSql .= "               ) as valor_importado_outros ";

        $sSql .= "         from tabrec                                                              							 ";
        $sSql .= "              inner join iptucadtaxaexe on iptucadtaxaexe.j08_tabrec = tabrec.k02_codigo						 ";
        $sSql .= "              inner join iptutaxanump   on iptutaxanump.j151_iptucadtaxaexe = iptucadtaxaexe.j08_iptucadtaxaexe";
        $sSql .= "              inner join iptutaxacalv   on iptutaxacalv.j152_iptutaxanump = j151_codigo						 ";
        $sSql .= "        where iptucadtaxaexe.j08_anousu = {$iAnoCalculo}                   			                         ";
        $sSql .= "        group by                                                                  							 ";
        $sSql .= "              tabrec.k02_codigo,                                                  							 ";
        $sSql .= "              iptucadtaxaexe.j08_anousu,                                        						         ";
        $sSql .= "              tabrec.k02_descr,                                                   							 ";
        $sSql .= "              iptutaxanump.j151_numpre) as x                                   						         ";
        $sSql .= " group by                                                                         							 ";
        $sSql .= "       ano_calculo,                                                               							 ";
        $sSql .= "       codigo_receita,                                                            							 ";
        $sSql .= "       descricao_receita                                                          							 ";
        $sSql .= " order by codigo_receita                                                          							 ";

        return $sSql;
    }
}
