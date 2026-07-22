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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once modification("libs/db_app.utils.php");

$oParametros = db_utils::postMemory($_GET);
$clmatestoque = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$cldb_almox = new cl_db_almox;

$clrotulo = new rotulocampo;
$clrotulo->label('m60_descr');
$clrotulo->label('descrdepto');

/**
 * busca o parametro de casas decimais para formatar o valor jogado na grid
 */

$oDaoParametros = new cl_empparametro;
$iAnoSessao = db_getsession("DB_anousu");
$sWherePeriodoParametro = " 1=1 limit 1";
$sSqlPeriodoParametro = $oDaoParametros->sql_query_file(null, "e30_numdec", null, $sWherePeriodoParametro);
$rsPeriodoParametro = $oDaoParametros->sql_record($sSqlPeriodoParametro);
$iParametroNumeroDecimal = db_utils::fieldsMemory($rsPeriodoParametro, 0)->e30_numdec;

$iAlt = 4;
$sOrderBy = "m80_data";
if (isset($oParametros->quebra) && $oParametros->quebra == "S") {
    $sOrderBy = ' m80_data, m80_coddepto';
} else {
    if ($oParametros->ordem == 'a') {
        $sOrderBy = 'm80_data, m70_codmatmater asc';
    } else {
        if ($oParametros->ordem == 'b') {
            $sOrderBy = 'm80_data, m80_coddepto  ';
        } else {
            if ($oParametros->ordem == 'c') {
                $sOrderBy = 'm80_data, m60_descr';
            } else {
                if ($oParametros->ordem == 'd') {
                    $sOrderBy = " m80_data asc";
                }
            }
        }
    }
}

if ($oParametros->listamatestoquetipo != "") {
    $sWhere = " m80_codtipo in ({$oParametros->listamatestoquetipo}) ";
} else {
    $sWhere = " m81_tipo = 2 ";
}

$sWhere .= " and m71_servico is false";
$sWhere .= " and origem.instit=" . db_getsession('DB_instit');

if ($oParametros->listaorgao != "") {
    $sWhere .= " and o40_orgao in ({$oParametros->listaorgao}) ";
    $sWhere .= " and o40_anousu = " . db_getsession('DB_anousu');
    $sWhere .= " and o40_instit=" . db_getsession('DB_instit');
}

if ($oParametros->listadepart != "") {
    if (isset ($oParametros->verdepart) && $oParametros->verdepart == "com") {
        $sWhere .= " and m80_coddepto in ({$oParametros->listadepart})";
    } else {
        $sWhere .= " and m80_coddepto not in ({$oParametros->listadepart})";
    }
}

if ($oParametros->listamat != "") {
    if (isset ($oParametros->vermat) && $oParametros->vermat == "com") {
        $sWhere .= " and m70_codmatmater in ({$oParametros->listamat})";
    } else {
        $sWhere .= " and m70_codmatmater not in ({$oParametros->listamat})";
    }
}

if ($oParametros->listausu != "") {
    if (isset ($oParametros->verusu) && $oParametros->verusu == "com") {
        $sWhere .= " and m80_login in ({$oParametros->listausu})";
    } else {
        $sWhere .= " and m80_login not in ({$oParametros->listausu})";
    }
}


if (isset($oParametros->listadepartDestino) && !empty($oParametros->listadepartDestino)) {
    $sWhere .= " and  m40_depto in ($oParametros->listadepartDestino) ";
}


/*
 * implementado logica para ir até os grupos caso eles venham selecionados
 */

$sInnerJoinGrupos = '';
$sFiltroGrupo = '';

if (isset($oParametros->grupos) && trim($oParametros->grupos) != "") {
    $sWhere .= " and materialestoquegrupo.m65_db_estruturavalor in ({$oParametros->grupos}) ";
    $sFiltroGrupo = 'Filtro por Grupos/Subgrupos';
    $head4 = $sFiltroGrupo;//"Relatório de Saída de Material por Departamento";
}

$sInnerJoinGrupos = "
    inner join matmatermaterialestoquegrupo on matmater.m60_codmater = matmatermaterialestoquegrupo.m68_matmater
    inner join materialestoquegrupo on matmatermaterialestoquegrupo.m68_materialestoquegrupo 
                                    = materialestoquegrupo.m65_sequencial
	";

$sDataIni = implode('-', array_reverse(explode('/', $oParametros->dataini)));
$sDataFin = implode('-', array_reverse(explode('/', $oParametros->datafin)));

if ((trim($oParametros->dataini) != "--") && (trim($oParametros->datafin) != "--")) {
    $sWhere .= " and m80_data between '{$sDataIni}' and '{$sDataFin}' ";
    $info = "De " . $oParametros->dataini . " até " . $oParametros->datafin;
} else {
    if (trim($oParametros->dataini) != "--") {
        $sWhere .= " and m80_data >= '{$sDataIni}' ";
        $info = "Apartir de " . $oParametros->dataini;
    } else {
        if (trim($oParametros->datafin) != "--") {
            $sWhere .= " and m80_data <= '{$sDataFin}' ";
            $info = "Até " . $oParametros->datafin;
        }
    }
}

$info_listar_serv = " LISTAR: TODOS";
$head3 = "Relatório de Saída de Material por Departamento";
$head5 = "$info";
$head7 = "$info_listar_serv";









$sSqlSaidas = "SELECT m65_db_estruturavalor, ";
$sSqlSaidas .= "       db121_estrutural,";
$sSqlSaidas .= "       db121_descricao, ";
$sSqlSaidas .= "       m80_codigo, ";
$sSqlSaidas .= "       m61_abrev,";
$sSqlSaidas .= "       db121_sequencial,";
$sSqlSaidas .= "       db121_nivel,";
$sSqlSaidas .= "       db121_estruturavalorpai as contapai, ";
$sSqlSaidas .= "       m70_coddepto,  ";
$sSqlSaidas .= "       m70_codmatmater, ";
$sSqlSaidas .= "       m80_coddepto, ";
$sSqlSaidas .= "       m60_descr,  ";
$sSqlSaidas .= "       origem.descrdepto,  ";
$sSqlSaidas .= "       sum(m82_quant) as qtde, ";
$sSqlSaidas .= "       m80_data,  ";
$sSqlSaidas .= "       m80_codtipo,  ";
$sSqlSaidas .= "       m83_coddepto,  ";
$sSqlSaidas .= "       m81_descr,  ";
$sSqlSaidas .= "       m41_codmatrequi, ";
$sSqlSaidas .= "       m89_precomedio as precomedio, ";
$sSqlSaidas .= "       sum(m89_valorfinanceiro) as m89_valorfinanceiro, ";
$sSqlSaidas .= "       coalesce(m40_depto, (

  select matestoqueinidestino.m80_coddepto 
   from  matestoqueini as matestoqueiniorigem 
  inner join matestoqueinill on matestoqueinill.m87_matestoqueini = matestoqueiniorigem.m80_codigo
  inner join matestoqueinil  on matestoqueinil.m86_matestoqueini = matestoqueinill.m87_matestoqueini
  inner join matestoqueinill as matestoqueinilldestino on matestoqueinilldestino.m87_matestoqueinil = matestoqueinil.m86_codigo
  inner join matestoqueini   as matestoqueinidestino on matestoqueinidestino.m80_codigo = matestoqueinilldestino.m87_matestoqueini
  inner join db_depart as destino on destino.coddepto = matestoqueinidestino.m80_coddepto
    where matestoqueiniorigem.m80_codigo = matestoqueini.m80_codigo


)) as m40_depto, 
                       destino.descrdepto as depto_destino, 
                       m89_valorunitario ";
$sSqlSaidas .= "  from matestoqueini  ";
$sSqlSaidas .= "       inner join matestoqueinimei    on m80_codigo              = m82_matestoqueini ";
$sSqlSaidas .= "       inner join matestoqueinimeipm  on m82_codigo              = m89_matestoqueinimei ";
$sSqlSaidas .= "       inner join matestoqueitem      on m82_matestoqueitem      = m71_codlanc  ";
$sSqlSaidas .= "       inner join matestoque          on m70_codigo              = m71_codmatestoque ";
$sSqlSaidas .= "       inner join matmater            on m70_codmatmater         = m60_codmater  ";
$sSqlSaidas .= "       inner join matestoquetipo      on m80_codtipo             = m81_codtipo  ";
$sSqlSaidas .= "       left  join matestoquetransf    on m83_matestoqueini       = m80_codigo   ";
$sSqlSaidas .= "       left  join matestoqueinimeiari on m49_codmatestoqueinimei = m82_codigo  ";
$sSqlSaidas .= "       left  join atendrequiitem      on m49_codatendrequiitem   = m43_codigo  ";
$sSqlSaidas .= "       left  join matrequiitem        on m41_codigo              = m43_codmatrequiitem ";
$sSqlSaidas .= "       left  join matrequi            on m40_codigo              = m41_codmatrequi ";
$sSqlSaidas .= "       left  join db_depart           on db_depart.coddepto      = matrequi.m40_depto ";
$sSqlSaidas .= "       left  join db_departorg        on db01_coddepto           = db_depart.coddepto  ";
$sSqlSaidas .= "                                     and db01_anousu             = " . db_getsession("DB_anousu");
$sSqlSaidas .= "       left  join orcorgao            on o40_orgao               = db_departorg.db01_orgao ";
$sSqlSaidas .= "                                     and o40_anousu              = " . db_getsession("DB_anousu");
$sSqlSaidas .= "       left  join matunid             on matmater.m60_codmatunid = matunid.m61_codmatunid ";

$sSqlSaidas .= $sInnerJoinGrupos; // string de inner caso venha grupos selecionados

$sSqlSaidas .= "       inner join db_estruturavalor on  m65_db_estruturavalor = db121_sequencial ";
$sSqlSaidas .= "       inner join db_depart origem on origem.coddepto = m70_coddepto 
                       left join db_depart destino on destino.coddepto = m40_depto";
$sSqlSaidas .= "      where {$sWhere} ";
$sSqlSaidas .= " group by m65_db_estruturavalor, destino.descrdepto,";
$sSqlSaidas .= "          db121_sequencial, ";
$sSqlSaidas .= "          db121_nivel, ";
$sSqlSaidas .= "          db121_estruturavalorpai,";
$sSqlSaidas .= "          db121_estrutural, ";
$sSqlSaidas .= "          db121_descricao, ";
$sSqlSaidas .= "          m80_codigo,  ";
$sSqlSaidas .= "          m61_abrev, ";
$sSqlSaidas .= "          m70_coddepto,  ";
$sSqlSaidas .= "          m70_codmatmater, ";
$sSqlSaidas .= "          m80_data,  ";
$sSqlSaidas .= "          m40_depto,  ";
$sSqlSaidas .= "          m81_descr,  ";
$sSqlSaidas .= "          m80_codtipo,  ";
$sSqlSaidas .= "          m80_coddepto,  ";
$sSqlSaidas .= "          m83_coddepto,  ";
$sSqlSaidas .= "         origem. descrdepto,  ";
$sSqlSaidas .= "          m89_precomedio,  ";
$sSqlSaidas .= "          m60_descr,  ";
$sSqlSaidas .= "          m41_codmatrequi, m89_valorunitario ";
$sSqlSaidas .= " order by m80_data, {$sOrderBy} , m80_data ";

if ($oParametros->ordem == 'b') {
    $sOrderBy = "m40_depto,  m80_data";
}


$sSqlSaidas = "
               select pai.db121_descricao as descr_pai,
                      pai.db121_estrutural as estrut_pai,
                      m40_depto as idepto_destino,
                      destino.descrdepto as sdepto_destino,
                      m70_coddepto as idepto_origem,
                      origem.descrdepto as sdepto_origem,
                      (precomedio * qtde) as total,
                      *
                   from (
                          {$sSqlSaidas}                  
                        ) as xx
                  inner join   db_estruturavalor pai on contapai = pai.db121_sequencial  
                  inner join db_depart origem on  m70_coddepto = origem.coddepto
                  inner join db_depart destino on  m40_depto = destino.coddepto
                  order by {$sOrderBy}
 ";







$rsSaidas = db_query($sSqlSaidas);
$iNumRows = pg_num_rows($rsSaidas);

if ($iNumRows <= 0 || !$rsSaidas) {

    $sMensagem = "Nenhum registro encontrado para o filtro selecionado.";
    db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMensagem);
    exit;
}

$aLinhas = array();

for ($i = 0; $i < $iNumRows; $i++) {
    $oItem = db_utils::fieldsMemory($rsSaidas, $i);

    $aLinhas[$oItem->m40_depto . " - " . $oItem->sdepto_destino]
    [$oItem->estrut_pai . " - " . $oItem->descr_pai]
    [$oItem->db121_estrutural . " - " . $oItem->db121_descricao]
    [$oItem->m80_data]
    [] = $oItem;

    unset($oItem);
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->SetAutoPageBreak(false);
$oPdf->AddPage("P");
$lEscreveHeader = true;
$iAlturalinha = 4;
$iTamFonte = 6;

$nTotalGeralQuant = 0;
$nTotalGeralValor = 0;
$oParametros->colunaEmBranco = $oParametros->iTipoRelatorio == '1' ? 35 : 50;

if (isset($oParametros->listadepart) && $oParametros->listadepart != "") {
    $aDepartamentosOrigem = explode(",", $oParametros->listadepart);

    if (count($aDepartamentosOrigem) > 0) {
        $oPdf->cell(70, $iAlturalinha, "Departamento(s) de Origem:", "", 1, "L", 0);

        foreach ($aDepartamentosOrigem as $iDepartamento) {
            $oDepartamento = new DBDepartamento($iDepartamento);
            $sDepartamento = $oDepartamento->getNomeDepartamento();
            $oPdf->cell(70, $iAlturalinha, "{$iDepartamento} - $sDepartamento  ", "", 1, "L", 0);
        }

        $oPdf->ln();
    }
}

foreach ($aLinhas as $sDestino => $aDestinos) {

    $nTotalDepartamento = 0;
    $nQuantTotalDepto = 0;

    $oPdf->SetFont('arial', 'b', $iTamFonte);
    $oPdf->cell(70, $iAlturalinha, "Departamento(s) de Destino:  {$sDestino}", "", 1, "L", 0);

    foreach ($aDestinos as $sGrupo => $aGrupos) {
        $oPdf->cell(10, $iAlturalinha, "", "", 0, "L", 0);
        $oPdf->cell(70, $iAlturalinha, "Grupo: $sGrupo", "", 1, "L", 0);

        foreach ($aGrupos as $sSupgrupo => $oSupGrupo) {
            $oPdf->SetFont('arial', 'b', $iTamFonte);
            $oPdf->cell(20, $iAlturalinha, "", "", 0, "L", 0);
            $oPdf->cell(50, $iAlturalinha, "Sub Grupo: $sSupgrupo", "", 1, "L", 0);

            imprimirCabecalho($oPdf, $iAlturalinha, true, $oParametros);
            $oPdf->SetFont('arial', '', $iTamFonte);

            $nTotalQuant = 0;
            $nValorTotal = 0;

            foreach ($oSupGrupo as $sData => $aDados) {
                $nQuantidade = 0;
                $nTotal = 0;

                foreach ($aDados as $oDados) {
                    $nQuantidade += $oDados->qtde;
                    $nTotal += $oDados->total;
                }

                $nTotalQuant += $nQuantidade;
                $nValorTotal += $nTotal;

                if($oParametros->iTipoRelatorio == '1') {
                    $oPdf->cell(35, $iAlturalinha, "", "", 0, "L", 0);
                    $oPdf->cell(15, $iAlturalinha, db_formatar($sData, "d"), "", 0, "C", 0);
                    $oPdf->cell(70, $iAlturalinha, $nQuantidade, "", 0, "C", 0);
                    $oPdf->cell(20, $iAlturalinha, db_formatar($nTotal, "f"), "", 1, "R", 0);
                }

                imprimirCabecalho($oPdf, $iAlturalinha, false, $oParametros);
            }

            $oPdf->SetFont('arial', 'b', $iTamFonte);
            $oPdf->cell(50, $iAlturalinha, "Total: ", "", 0, "R", 0);
            $oPdf->cell(70, $iAlturalinha, $nTotalQuant, "", 0, "C", 0);
            $oPdf->cell(20, $iAlturalinha, db_formatar($nValorTotal, "f"), "", 1, "R", 0);
            $nTotalDepartamento += $nValorTotal;
            $nQuantTotalDepto += $nTotalQuant;
            $oPdf->SetFont('arial', '', $iTamFonte);
            $oPdf->ln(2);

            $nTotalGeralQuant += $nTotalQuant;
            $nTotalGeralValor += $nValorTotal;
        }

    }

   // echo "<br> Total Depto: ";
    $oPdf->SetFont('arial', 'b', $iTamFonte);
    $oPdf->cell(70, $iAlturalinha, "Total do Departamento:  {$sDestino}", "", 0, "L", 1);
    $oPdf->cell(20, $iAlturalinha, $nQuantTotalDepto, "", 0, "R", 1);
    $oPdf->cell(50, $iAlturalinha, db_formatar($nTotalDepartamento, "f"), "", 1, "R", 1);
    $oPdf->SetFont('arial', '', $iTamFonte);
    
    }

//die();

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->cell(50, $iAlturalinha, "Total Geral: ", "", 0, "R", 0);
$oPdf->cell(70, $iAlturalinha, $nTotalGeralQuant, "", 0, "C", 0);
$oPdf->cell(20, $iAlturalinha, db_formatar($nTotalGeralValor, "f"), "", 1, "R", 0);
$oPdf->SetFont('arial', '', $iTamFonte);

$oPdf->Output();

function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime, $oParametros)
{
    $iTamFonte = 6;
    if ($oPdf->GetY() > $oPdf->h - 25 || $lImprime) {
        $oPdf->SetFont('arial', 'b', $iTamFonte);

        if (!$lImprime) {
            $oPdf->AddPage("P");
        }

        $oPdf->setfont('arial', 'b', $iTamFonte);
        $oPdf->cell($oParametros->colunaEmBranco, $iAlturalinha, "", "", 0, "L", 0);

        if($oParametros->iTipoRelatorio == '1') {
            $oPdf->cell(15, $iAlturalinha, "Data", "", 0, "C", 1);
        }

        $oPdf->cell(70, $iAlturalinha, "Quantidade", "", 0, "C", 1);
        $oPdf->cell(20, $iAlturalinha, "Total", "", 1, "C", 1);
        $oPdf->SetFont('arial', '', $iTamFonte);
    }
}
