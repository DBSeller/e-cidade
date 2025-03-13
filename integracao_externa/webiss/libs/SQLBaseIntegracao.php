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

abstract class SQLBaseIntegracao {

  /**
   * Retorna os Dados da Guia conforme Codigo
   * @param integer $iCodigoSequencialGuia
   * @param integer $iNumDoc
   */
  static public function sql_query_DadosGuia($iCodigoSequencialGuia, $iNumDoc) {
    
    $sSqlCompetenciasGuia = " select *                                                                                                               ";
    $sSqlCompetenciasGuia.= "   from ( select integra_recibo_detalhe.numpre,                                                                         ";
    $sSqlCompetenciasGuia.= "                 integra_recibo_detalhe.numpar,                                                                         ";
    $sSqlCompetenciasGuia.= "                 numdoc,                                                                                                ";
    $sSqlCompetenciasGuia.= "                 integra_recibo.sequencial,                                                                             ";
    $sSqlCompetenciasGuia.= "                 case                                                                                                   ";
    $sSqlCompetenciasGuia.= "                   when integra_recibo_detalhe_origem is null then null                                                 ";
    $sSqlCompetenciasGuia.= "                   else ( select distinct                                                                               ";
    $sSqlCompetenciasGuia.= "                                 x.numdoc                                                                               ";
    $sSqlCompetenciasGuia.= "                            from integra_recibo x                                                                       ";
    $sSqlCompetenciasGuia.= "                                 inner join integra_recibo_detalhe y on y.integra_recibo = x.sequencial                 ";
    $sSqlCompetenciasGuia.= "                           where y.sequencial = integra_recibo_detalhe.integra_recibo_detalhe_origem )                  ";
    $sSqlCompetenciasGuia.= "                 end as numdoc_guia_origem,                                                                             ";
    $sSqlCompetenciasGuia.= "                 ano_competencia_origem,                                                                                ";
    $sSqlCompetenciasGuia.= "                 mes_competencia_origem                                                                                 ";
    $sSqlCompetenciasGuia.= "            from integra_recibo                                                                                         ";
    $sSqlCompetenciasGuia.= "                 inner join integra_recibo_detalhe on integra_recibo_detalhe.integra_recibo = integra_recibo.sequencial ";
    $sSqlCompetenciasGuia.= "           where integra_recibo.numdoc     = {$iNumDoc}                                                                 ";
    $sSqlCompetenciasGuia.= "             and integra_recibo.sequencial = {$iCodigoSequencialGuia}                                                   ";
    $sSqlCompetenciasGuia.= "        ) as x                                                                                                          ";
    $sSqlCompetenciasGuia.= "  where numdoc_guia_origem is null or numdoc <> numdoc_guia_origem                                                      ";
    $sSqlCompetenciasGuia.= "  order by numdoc;                                                                                                      ";

    return $sSqlCompetenciasGuia;
  }

  /**
   * Retorna os Dados da Guia conforme Codigo
   * @param integer $iCodigoSequencialGuia
   * @param integer $iNumDoc
   */
  static public function sql_query_DadosGuiaCompetencia($iCodigoSequencialGuia, $iNumDoc) {
    
    $sSqlDebitosGuia = " select distinct                                                                                                        ";
    $sSqlDebitosGuia.= "        numpre,                                                                                                         ";
    $sSqlDebitosGuia.= "        numpar,                                                                                                         ";
    $sSqlDebitosGuia.= "        processado,                                                                                                     ";
    $sSqlDebitosGuia.= "        integra_recibo,                                                                                                 ";
    $sSqlDebitosGuia.= "        numdoc                                                                                                          ";
    $sSqlDebitosGuia.= "   from integra_recibo_detalhe                                                                                          ";
    $sSqlDebitosGuia.= "        inner join integra_recibo x on x.sequencial = integra_recibo_detalhe.integra_recibo                             ";
    $sSqlDebitosGuia.= "  where integra_recibo  in (select distinct                                                                             ";
    $sSqlDebitosGuia.= "                              case when integra_recibo_detalhe_origem is null                                           ";
    $sSqlDebitosGuia.= "                                   then integra_recibo                                                                  ";
    $sSqlDebitosGuia.= "                                   else (select sub.integra_recibo                                                      ";
    $sSqlDebitosGuia.= "                                           from integra_recibo_detalhe as sub                                           ";
    $sSqlDebitosGuia.= "                                          where sub.sequencial = integra_recibo_detalhe.integra_recibo_detalhe_origem ) ";
    $sSqlDebitosGuia.= "                              end                                                                                       ";
    $sSqlDebitosGuia.= "                         from integra_recibo_detalhe                                                                    ";
    $sSqlDebitosGuia.= "                        where integra_recibo in ( select max(sequencial)                                                ";
    $sSqlDebitosGuia.= "                                                    from integra_recibo                                                 ";
    $sSqlDebitosGuia.= "                                                   where numdoc = {$iNumDoc} )                                          ";
    $sSqlDebitosGuia.= "                      )                                                                                                 ";
    $sSqlDebitosGuia.= "    and numpre is not null                                                                                              ";
    $sSqlDebitosGuia.= "    and numpar is not null                                                                                              ";
    $sSqlDebitosGuia.= "    and integra_recibo in ( select max(sequencial)                                                                      ";
    $sSqlDebitosGuia.= "                              from integra_recibo                                                                       ";
    $sSqlDebitosGuia.= "                             where numdoc = x.numdoc )                                                                  ";

    return $sSqlDebitosGuia;
  }
}