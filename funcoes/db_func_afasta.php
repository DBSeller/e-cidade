<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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

$campos = "afasta.r45_codigo as db_r45_codigo,
           afasta.r45_regist,
           z01_numcgm,
           z01_nome,
					 case afasta.r45_situac 
					      when 1 then 'Normal'
					      when 2 then 'Afastado sem remuneração'
					      when 3 then 'Afastado acidente de trabalho +15 dias'
					      when 4 then 'Afastado serviço militar'
					      when 5 then 'Afastado licença gestante'
					      when 6 then 'Afastado doença +15 dias'
					      when 7 then 'Licença sem vencimento, cessão sem ônus'
					      When 8 then 'Afastado doença +30 dias'
					      When 9 then 'Prorrogação Licença Maternidade'
                          when 10 then 'Licença para cuidar de familiar'
                          when 11 then 'Licença Prêmio'
					 end as r45_situac,
           afasta.r45_dtlanc,
           afasta.r45_dtafas,
           afasta.r45_dtreto,
           afasta.r45_anousu as db_r45_anousu,
           afasta.r45_mesusu as db_r45_mesusu";
//,afasta.r45_situac,afasta.r45_codafa,afasta.r45_codret
?>
