<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
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

require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("fpdf151/pdf.php"));
// require_once(modification("libs/db_liborcamento.php"));
// require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification('libs/db_stdlib.php'));

db_app::import("contabilidade.relatorios.AnexoSumarioGeralReceita");
db_app::import("relatorioContabil");
$oGet         = db_utils::postMemory($_GET);
$iAnoUso      = db_getsession('DB_anousu');
$sInstit      = str_replace("-", ",", $oGet->sInstit);
$oAnexoSumario = new AnexoSumarioGeralReceita($iAnoUso, $oGet->iCodRel, 17);
$oAnexoSumario->setOrigemFase(1);
// $oAnexoSumario->setInstituicoes($sInstit);
// $aDadosSumarioGeral = $oAnexoSumario->getDados();

// $aFases         = array(1 => "Orçamento",
//                         2 => "Empenhado",
//                         3 => "Liquidado",
//                         4 => "Pago");
$rsInstituicoes = pg_exec("select codigo, nomeinst, nomeinstabrev
                             from db_config
                            where codigo in ({$sInstit}) ");
$sDescricaoInstitucoes = '';
$sVirg                 = '';
$lAbrevia              = false;
for ($iInstit = 0; $iInstit < pg_num_rows($rsInstituicoes); $iInstit++) {

  $oInstit = db_utils::fieldsmemory($rsInstituicoes, $iInstit);
  if (strlen(trim($oInstit->nomeinstabrev)) > 0) {

    $sDescricaoInstitucoes .= $sVirg.$oInstit->nomeinstabrev;
    $lAbrevia               = true;
  } else {
    $sDescricaoInstitucoes .= $sVirg.$oInstit->nomeinst;
  }
  $sVirg = ', ';
}
if ($lAbrevia) {

  if (strlen($sDescricaoInstitucoes) > 42) {
    $sDescricaoInstitucoes = substr($sDescricaoInstitucoes, 0, 150);
  }
}

/**
 * Busca o periodo
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oAnexoSumario->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {

  if ($oPeriodo->o114_sequencial == $oGet->iPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}
$head1 = db_stdClass::getDadosInstit(db_getsession("DB_instit"))->nomeinst;
$head2 = "SUMÁRIO GERAL DA RECEITA POR FONTES";
$head3 = "Lei Orçamentária Anual de ".db_getsession("DB_anousu");
$head4 = "Instituições: {$sDescricaoInstitucoes}";

/**
 * Se a Origem/Fase for diferente de Orçamento
 */
// if ($oGet->iOrigemFase != 1) {

//   $head5 = "Valor: {$aFases[$oGet->iOrigemFase]}";
//   $head6 = "Período: JANEIRO a {$sDescricaoPeriodo}";
// }

//$sqlTmp = "create temp table work_receita as select substr(o57_fonte,1,1)::int4 as classe, substr(o57_fonte,2,1)::int4 as grupo, substr(o57_fonte,3,1)::int4 as subgrupo, substr(o57_fonte,4,1)::int4 as elemento, substr(o57_fonte,5,1)::int4 as subelemento, substr(o57_fonte,6,2)::int4 as item, substr(o57_fonte,8,2)::int4 as subitem, substr(o57_fonte,10,2)::int4 as desdobramento1, substr(o57_fonte,12,2)::int4 as desdobramento2, substr(o57_fonte,14,2)::int4 as desdobramento3, o70_codrec, o70_concarpeculiar, o70_codigo, o70_codfon, cast(coalesce(nullif(substr(fc_receitasaldo, 3,12),''),'0') as float8) as saldo_inicial, cast(coalesce(nullif(substr(fc_receitasaldo,16,12),''),'0') as float8) as saldo_prevadic_acum, cast(coalesce(nullif(substr(fc_receitasaldo,29,12),''),'0') as float8) as saldo_inicial_prevadic, cast(coalesce(nullif(substr(fc_receitasaldo,42,12),''),'0') as float8) as saldo_anterior, cast(coalesce(nullif(substr(fc_receitasaldo,55,12),''),'0') as float8) as saldo_arrecadado, cast(coalesce(nullif(substr(fc_receitasaldo,68,12),''),'0') as float8) as saldo_a_arrecadar, cast(coalesce(nullif(substr(fc_receitasaldo,81,12),''),'0') as float8) as saldo_arrecadado_acumulado, cast(coalesce(nullif(substr(fc_receitasaldo,94,12),''),'0') as float8) as saldo_prev_anterior from(select o70_anousu, o70_codrec, o70_codfon, o70_codigo, o70_valor, o70_reclan, o70_instit, o70_concarpeculiar, o57_codfon, o57_anousu, o57_fonte, o57_descr, o57_finali, fc_receitasaldo(2015,o70_codrec,3,'2015-01-01','2015-1-31') from orcreceita d inner join orcfontes e on d.o70_codfon = e.o57_codfon and e.o57_anousu = d.o70_anousu where o70_anousu = 2015 and o70_instit in (1) order by o57_fonte ) as x ";
//$rsTmp = pg_query($sqlTmp);
$sqlReceita = "select case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(o70_valor) end as tesouro,
case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(o70_valor) end as outros,
o70_codigo,
o15_descr,
gestao
from orcreceita
inner join orcfontes on o57_codfon = o70_codfon and o57_anousu = o70_anousu
inner join orctiporec on o70_codigo = o15_codigo
inner join fonterecurso on orctiporec_id = o15_codigo
where o70_anousu = ".db_getsession("DB_anousu")." and o70_instit in ($sInstit) group by o70_codigo, o15_descr, gestao order by o70_codigo";
//$sqlReceita = "select classe, 0 as grupo, 0 as subgrupo, 0 as elemento, 0 as subelemento, 0 as item, 0 as subitem, 0 as desdobramento1, 0 as desdobramento2, 0 as desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe) as a union all select classe, grupo, 0 as subgrupo, 0 as elemento, 0 as subelemento, 0 as item, 0 as subitem, 0 as desdobramento1, 0 as desdobramento2, 0 as desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo ) as a union all select classe, grupo, subgrupo, 0 as elemento, 0 as subelemento, 0 as item, 0 as subitem, 0 as desdobramento1, 0 as desdobramento2, 0 as desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, subgrupo, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo, subgrupo ) as c union all select classe, grupo, subgrupo, elemento, 0 as subelemento, 0 as item, 0 as subitem, 0 as desdobramento1, 0 as desdobramento2, 0 as desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, subgrupo, elemento, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo, subgrupo, elemento ) as d union all select classe, grupo, subgrupo, elemento, subelemento, 0 as item, 0 as subitem, 0 as desdobramento1, 0 as desdobramento2, 0 as desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, subgrupo, elemento, subelemento, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo, subgrupo, elemento, subelemento ) as e union all select classe, grupo, subgrupo, elemento, subelemento, item, 0 as subitem, 0 as desdobramento1, 0 as desdobramento2, 0 as desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, subgrupo, elemento, subelemento, item, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo, subgrupo, elemento, subelemento, item ) as f union all select classe, grupo, subgrupo, elemento, subelemento, item, subitem, 0 as desdobramento1, 0 as desdobramento2, 0 as desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, subgrupo, elemento, subelemento, item, subitem, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo, subgrupo, elemento, subelemento, item, subitem ) as g union all select classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, 0 as desdobramento2, 0 as desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1 ) as h union all select classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, 0 as desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2 ) as i union all select classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, desdobramento3, 0 as o70_codrec, '0' as o70_concarpeculiar, 0 as o70_codigo, 0 as o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, desdobramento3, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, desdobramento3 ) as l union all select classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, desdobramento3, o70_codrec, o70_concarpeculiar, o70_codigo, o70_codfon, saldo_inicial, saldo_prevadic_acum, saldo_inicial_prevadic, saldo_anterior, saldo_arrecadado, saldo_a_arrecadar, saldo_arrecadado_acumulado, saldo_prev_anterior/*, tesouro, outros*/ from ( select classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, desdobramento3, o70_codrec, o70_concarpeculiar, o70_codigo, o70_codfon, sum(saldo_inicial) as saldo_inicial, sum(saldo_prevadic_acum) as saldo_prevadic_acum, sum(saldo_inicial_prevadic) as saldo_inicial_prevadic, sum(saldo_anterior) as saldo_anterior, sum(saldo_arrecadado) as saldo_arrecadado, sum(saldo_a_arrecadar) as saldo_a_arrecadar, sum(saldo_arrecadado_acumulado) as saldo_arrecadado_acumulado, sum(saldo_prev_anterior) as saldo_prev_anterior/*, case when o70_codigo = 100 or o70_codigo = 203 or o70_codigo = 209 then sum(saldo_arrecadado_acumulado) end as tesouro, case when o70_codigo <> 100 and o70_codigo <> 203 and o70_codigo <> 209 then sum(saldo_arrecadado_acumulado) end as outros*/ from work_receita group by classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, desdobramento3, o70_codrec, o70_codigo, o70_codfon, o70_concarpeculiar ) as m order by classe, grupo, subgrupo, elemento, subelemento, item, subitem, desdobramento1, desdobramento2, desdobramento3, o70_codrec, o70_concarpeculiar, o70_codigo, o70_codfon";
$rsReceita = pg_query($sqlReceita);

$aReceita = array();

for ($i = 0; $i < pg_num_rows($rsReceita); $i++) {
	db_fieldsmemory($rsReceita, $i);
	$aReceita[$i]['codigo'] = $gestao;
	$aReceita[$i]['descricao'] = $o15_descr;
	$aReceita[$i]['valorTesouro'] = $tesouro;
	$aReceita[$i]['valorOutros'] = $outros;
}

if ($oGet->lConsolidado == 1) {
  $head2 .= " - Consolidado";
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 7);
$iAlt         = 4;
$iPagina      = 1;
$iTamFonte    = 8;
$iFonte       = 7;
$iColunaDescr = 60;
$iColunaValor = 25;

$oPdf->addpage();
$oPdf->setfont('arial', 'b', $iFonte);
$oPdf->cell(190, $iAlt, "R E C E I T A S", 1, 1, "C", 0);
//$oPdf->cell(95, $iAlt, "D E S P E S A S", 0, 1, "C", 0);
// $iYInicial     = $oPdf->getY()+3;
// $oPdf->line(10, $iYInicial-3, 205, $iYInicial-3);
// $iValrYDespesa = 0;
// $iValrYReceita = 0;
$oPdf->ln(3);
$oPdf->cell(10, $iAlt, "", 1, 0, "R", 0);
$oPdf->cell(58, $iAlt, "", 1, 0, "R", 0);
$oPdf->cell(42, $iAlt, "Recursos do Tesouro", 1, 0, "R", 0);
$oPdf->cell(42, $iAlt, "Recursos de Outras Fontes", 1, 0, "R", 0);
$oPdf->cell(42, $iAlt, "Total", 1, 1, "R", 0);
$oPdf->setfont('arial','', $iFonte);

// echo'<pre>';var_dump($aReceita); die;

$total = 0;

foreach ($aReceita as $key => $value) {
    $oPdf->setfont('arial','B',$iFonte);
	$oPdf->cell(10, $iAlt, $value['codigo'], 1, 0, "L", 0);
    $oPdf->cell(58, $iAlt, $value['descricao'], 1, 0, "L", 0);
	$oPdf->cell(42, $iAlt, db_formatar($value['valorTesouro'], 'f'), 1, 0, "R", 0);
	$oPdf->cell(42, $iAlt, db_formatar($value['valorOutros'], 'f'), 1, 0, "R", 0);

	$oPdf->cell(42, $iAlt, db_formatar($value['valorTesouro']+$value['valorOutros'], 'f'), 1, 1, "R", 0);
	$total += $value['valorTesouro']+$value['valorOutros'];
} // fim foreachc

$oPdf->cell(152, $iAlt, "T O T A L", 1, 0, "R", 0);
$oPdf->cell(42, $iAlt, db_formatar($total, 'f'), 1, 0, "R", 0);


// foreach ($aDadosSumarioGeral as $iIdLinha => $oRelatorio) {
//   if ($iIdLinha != 22) {
//     /**
//      * Valida totalizador
//      */
//     $sBold = "";
//     if ($oRelatorio->totalizar) {
//       $sBold = "b";
//     }

//     if ($iIdLinha <= 19) {

//       if ($iIdLinha == 1) {
//         $oPdf->setfont('arial','B',$iFonte);
//         $oPdf->cell(170, $iAlt, setIdentacao($oRelatorio->nivellinha).$oRelatorio->descricao, 0, 0, "L", 0);
//         $oPdf->cell($iColunaValor, $iAlt, db_formatar($oRelatorio->total, 'f'), 0, 1, "R", 0);
//       } else {
//         $oPdf->setfont('arial',$sBold, $iFonte);
//         $oPdf->cell(170, $iAlt, setIdentacao($oRelatorio->nivellinha).$oRelatorio->descricao, 0, 0, "L", 0,'','.');
//         $oPdf->cell($iColunaValor, $iAlt, db_formatar($oRelatorio->total, 'f'), 0, 1, "R", 0);
//       }

//       $iValrYReceita = $oPdf->GetY();
//     }

//     // if ($iIdLinha == 22) {

//     //   $sBold = "";
//    //    if ($oRelatorio->totalizar) {
//    //      $sBold = "b";
//    //    }
//     //   $oPdf->setXY(100, $iYInicial);
//    //   $oPdf->setfont('arial','B',$iFonte);
//    //   $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oRelatorio->nivellinha).$oRelatorio->descricao, 0, 0, "L", 0);
//    //   $oPdf->cell($iColunaValor, $iAlt, db_formatar($oRelatorio->total, 'f')                        , 0, 1, "R", 0);

//    //    foreach ($oRelatorio->funcoes as $iIdFuncao => $oFuncao) {

//    //      if ($oFuncao->total != 0) {

//    //        $oPdf->setX(100);
//    //        $oPdf->setfont('arial',$sBold, $iFonte);
//    //        $oPdf->cell($iColunaDescr, $iAlt, "  ".str_pad($iIdFuncao, 2, "0", STR_PAD_LEFT)." - ". $oFuncao->descricao, 0, 0, "L", 0,'','.');
//    //        $oPdf->cell($iColunaValor, $iAlt, db_formatar($oFuncao->total, 'f'), 0, 1, "R", 0);
//    //      }
//    //    }
//    //    $iValrYDespesa = $oPdf->GetY();
//     // }
//   }

// }

// if ($iValrYReceita > $iValrYDespesa) {
//   $iValorYPatronais = ($iValrYReceita+5);
// } else {
//   $iValorYPatronais = ($iValrYDespesa+5);
// }
//$oPdf->line(10, $iValorYPatronais, 205, $iValorYPatronais);

//$iValorYTotal = $oPdf->getY();
//$oPdf->line(10, $iValorYTotal, 205, $iValorYTotal);
/**
 * Valores Totais
 */
// $oPdf->setfont('arial','B',$iFonte);
// $oPdf->cell(170, $iAlt, setIdentacao($aDadosSumarioGeral[21]->nivellinha).$aDadosSumarioGeral[21]->descricao, 0, 0, "L", 0);
// $oPdf->cell($iColunaValor, $iAlt, db_formatar($aDadosSumarioGeral[21]->total, 'f')                        , 0, 1, "R", 0);

// $oPdf->setXY(100, $iValorYTotal);

// $oPdf->setfont('arial','B',$iFonte);
// $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($aDadosSumarioGeral[24]->nivellinha).$aDadosSumarioGeral[24]->descricao, 0, 0, "L", 0);
// $oPdf->cell($iColunaValor, $iAlt, db_formatar($aDadosSumarioGeral[24]->total, 'f')                        , 0, 1, "R", 0);
//$oPdf->line(98, $iYInicial-3, 98, $oPdf->GetY());
$oPdf->line(10, $oPdf->GetY(), 200, $oPdf->GetY());
$oPdf->Output();


function setIdentacao($iNivel) {

  $sEspaco = "";
  if ($iNivel > 1) {
    $sEspaco = str_repeat("   ", $iNivel);
  }
  return $sEspaco;
}
?>
