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
require_once(modification("std/DBArray.php"));
require_once(modification("model/pessoal/relatorios/resumoFolha.model.php"));
require_once(modification("model/pessoal/ServidorRepository.model.php"));
require_once(modification("libs/JSON.php"));

$oJson = new services_json();
$oGet = db_utils::postMemory($_GET);
$iInstituicao = db_getsession("DB_instit");
$oParametros = $oJson->decode(str_replace("\\", "", $oGet->json));

try {

    /**
     * constantes para o Relatorio
     */
    define("TIPO_RELATORIO_GERAL", "0");
    define("TIPO_RELATORIO_ORGAO", "1");
    define("TIPO_RELATORIO_LOTACAO", "2");
    define("TIPO_RELATORIO_MATRICULA", "3");
    define("TIPO_RELATORIO_LOCAIS_TRABALHO", "4");
    define("TIPO_RELATORIO_CARGO", "5");
    define("TIPO_RELATORIO_RECURSO", "6");

    define("TIPO_FILTRO_GERAL", 0);
    define("TIPO_FILTRO_INTERVALO", 1);
    define("TIPO_FILTRO_SELECIONADOS", 2);

    define("TIPO_VINCULO_GERAL", "g");
    define("TIPO_VINCULO_ATIVOS", "a");
    define("TIPO_VINCULO_INATIVOS", "i");
    define("TIPO_VINCULO_PENSIONISTAS", "p");
    define("TIPO_VINCULO_INATIVOS_PENSIONISTAS", "ip");

    define("TIPO_PREVIDENCIA_SEM_PREVIDENCIA", 5);

    define("ORDENACAO_RELATORIO_NUMERICA", "n");
    define("ORDENACAO_RELATORIO_ALFABETICA", "a");

    $aWhere = array();
    $sDescricaoSelecao = '';

    /**
     * Valida se existe seleção
     */
    if (!empty($oParametros->iSelecao)) {

        $sSelecao = trim(db_utils::getDao("selecao")->getCondicaoSelecao($oParametros->iSelecao));

        if (!empty($sSelecao)) {
            $aWhere['selecao'] = $sSelecao;

            $sDescricaoSelecao = trim(db_utils::getDao("selecao")->getDescricaoSelecao($oParametros->iSelecao, $iInstituicao));
            $sDescricaoSelecao = "\nSELEÇÃO : " . $sDescricaoSelecao;
        }
    }

    /**
     * Valida se existe Regime
     */
    if (!empty($oParametros->iRegime)) {

        $aWhere['regime'] = "rh30_regime = {$oParametros->iRegime}";
    }

    $head3 = 'TIPO FILTRO : ';

    switch ($oParametros->iTipoRelatorio) {

        default:

            $sLabelTipoRelatorio = "Geral";
            $sCampoCondicaoTipoRelatorio = 1;
            $sCampoEstruturalTipoRelatorio = 1;
            $sCampoDescricaoTipoRelatorio = "'GERAL'";
            $head3 .= 'GERAL';

            break;

        case TIPO_RELATORIO_CARGO:

            $sLabelTipoRelatorio = "Cargos:";
            $sCampoCondicaoTipoRelatorio = "rh37_funcao";
            $sCampoEstruturalTipoRelatorio = "rh37_funcao";
            $sCampoDescricaoTipoRelatorio = "rh37_descr";
            $head3 .= 'CARGOS';

            break;

        case TIPO_RELATORIO_LOTACAO:

            $sLabelTipoRelatorio = "Lotações:";
            $sCampoCondicaoTipoRelatorio = "r70_codigo";
            $sCampoEstruturalTipoRelatorio = "r70_estrut";
            $sCampoDescricaoTipoRelatorio = "r70_descr";
            $head3 .= 'LOTAÇÕES';

            break;

        case TIPO_RELATORIO_ORGAO:

            $sLabelTipoRelatorio = "Órgãos:";
            $sCampoCondicaoTipoRelatorio = "rh26_orgao";
            $sCampoEstruturalTipoRelatorio = "rh26_orgao";
            $sCampoDescricaoTipoRelatorio = "o40_descr";
            $head3 .= 'ÓRGÃOS';

            break;

        case TIPO_RELATORIO_LOCAIS_TRABALHO:

            $sLabelTipoRelatorio = "Locais de Trabalho:";
            $sCampoCondicaoTipoRelatorio = "rh55_codigo";
            $sCampoEstruturalTipoRelatorio = "rh55_estrut";
            $sCampoDescricaoTipoRelatorio = "rh55_descr";
            $head3 .= 'LOCAIS DE TRABALHO';

            break;

        case TIPO_RELATORIO_MATRICULA:

            $sLabelTipoRelatorio = "Matrículas:";
            $sCampoCondicaoTipoRelatorio = "rh02_regist";
            $sCampoEstruturalTipoRelatorio = "rh02_regist";
            $sCampoDescricaoTipoRelatorio = "z01_nome";
            $head3 .= 'MATRÍCULAS';

            break;

    }

    if ($oParametros->iTipoRelatorio <> TIPO_RELATORIO_GERAL) {

        switch ($oParametros->iTipoFiltro) {

            case TIPO_FILTRO_GERAL:
                //Sem Filtros
                break;
            case TIPO_FILTRO_INTERVALO:
                $aWhere['tipo_filtro' . $oParametros->iTipoRelatorio] = "{$sCampoCondicaoTipoRelatorio} between $oParametros->iIntervaloInicial and $oParametros->iIntervaloFinal";
                break;
            case TIPO_FILTRO_SELECIONADOS:
                $aWhere['tipo_filtro' . $oParametros->iTipoRelatorio] = "{$sCampoCondicaoTipoRelatorio} in (" . implode(", ", $oParametros->iRegistros) . ")";
                break;
        }
    }

    /**
     * Definições Sobre Vinculo
     */
    if (!empty($oParametros->sVinculo)) {

        switch ($oParametros->sVinculo) {

            default:
                $sTituloVinculo = "GERAL";
                $sCondicaoVinculo = null;
                break;


            case TIPO_VINCULO_ATIVOS:
                $sTituloVinculo = "ATIVOS";
                $sCondicaoVinculo = " rh30_vinculo = 'A' ";
                break;

            case TIPO_VINCULO_INATIVOS:
                $sTituloVinculo = "INATIVOS";
                $sCondicaoVinculo = " rh30_vinculo = 'I' ";
                break;

            case TIPO_VINCULO_PENSIONISTAS:
                $sTituloVinculo = "PENSIONISTAS";
                $sCondicaoVinculo = " rh30_vinculo = 'P' ";
                break;

            case TIPO_VINCULO_INATIVOS_PENSIONISTAS:
                $sTituloVinculo = "INATIVOS / PENSIONISTAS";
                $sCondicaoVinculo = " rh30_vinculo in ('I','P') ";
                break;
        }

        if (!empty($sCondicaoVinculo)) {
            $aWhere['vinculo'] = $sCondicaoVinculo;
        }
    }

    if (!empty($sDescricaoSelecao)) {
        $head3 .= $sDescricaoSelecao;
    }

    $ordem = "";
    switch ($oParametros->iTipoOrdem) {

        case "0":
            $ordem= "rh55_descr";
            break;

        case "1":

            $ordem = "rh55_codigo, rh55_instit";
            break;

        case "2":

            $ordem = "r30_perai";
             break;

        case "3":
            $ordem = "r30_peraf";
            break;

        default:

            $ordem = "rh55_descr";
            break;
    }

    $dataInicial = "{$oParametros->iAno}-{$oParametros->iMes}-01";
    $dataFinal = "{$oParametros->iAno}-{$oParametros->iMes}-" . cal_days_in_month(CAL_GREGORIAN, $oParametros->iMes, $oParametros->iAno);

    $aWhere [] = "((extract(year from r30_per1i) = {$oParametros->iAno} and extract(month from r30_per1i) = {$oParametros->iMes})
                   or (extract(year from r30_per2i) = {$oParametros->iAno} and extract(month from r30_per2i) = {$oParametros->iMes}))";

    if ($oParametros->iServidorAfastado  == 0) {

        $aWhere[] = " not exists(select 1 
                                 from afasta 
                                where r45_anousu = {$oParametros->iAno} 
                                  and r45_mesusu = {$oParametros->iMes}
                                  and r45_regist = rh01_regist
                                  and (r45_dtafas between '{$dataInicial}' and '{$dataFinal}'
                                           or (r45_dtreto is null or r45_dtreto between '{$dataInicial}' and '{$dataFinal}')
                                      )
                               )";
    }

    $oDaoRhPessoalMov = new cl_rhpessoalmov;
    $iInstituicao = db_getsession('DB_instit');
    $sWhere = implode(' and ', $aWhere);
    $sCampos = "distinct rh01_regist as matricula,                                     ";
    $sCampos .= "r30_perai as periodo_aquisitivo_inicial, ";
    $sCampos .= "r30_peraf as periodo_aquisitivo_final,";
    $sCampos .= "rh55_codigo, rh55_instit,";
    $sCampos .= "z01_nome as nome,";
    $sCampos .= "rh55_descr as local_trabalho,";
    $sCampos .= " '  ' as faltas,";
    $sCampos .= "   case when r30_per1i between '{$dataInicial}' and '{$dataFinal}' then r30_per1i else r30_per2i end  as periodogozoinicial,";
    $sCampos .= "   case when r30_per1i between '{$dataInicial}' and '{$dataFinal}' then r30_per1f else r30_per2f end  as periodogozofinal";

    $iAnoFolha =  DBPessoal::getAnoFolha();
    $iMesFolha =  DBPessoal::getMesFolha();

    $sSqlServidores = sql_query_baseServidores($iMesFolha,
        $iAnoFolha,
        $iInstituicao,
        $sCampos,
        $sWhere,
        $ordem,
        null);


    $rsServidores = db_query($sSqlServidores);

    if (!$rsServidores) {
        throw new DBException("Erro ao Buscar os Servidores pelos filtros selecionados. \n" . pg_last_error());
    }

    if (pg_num_rows($rsServidores) == 0) {
        throw new BusinessException("Nenhum Servidor encontrado nos Filtros Selecionados");
    }

    $aDadosRelatorios = db_utils::getCollectionByRecord($rsServidores);


    $headers = array('Matricula',
        'Nome',
        'Periodo Aquisitivo Inicial',
        'Periodo Aquisitivo Final',
        'Periodo de gozo inicial',
        'Periodo de gozo final',
        'Local de trabalho',
        'Faltas');

    if ($oParametros->iFormato == 1) {

        $arquivo = 'tmp/relatorio_ferias_programadas.csv';
        $rArquivo = fopen($arquivo, 'w');
        fputs($rArquivo, implode(",", $headers) . "\n");
        foreach ($aDadosRelatorios as $registro) {
            $linha = array(
                $registro->matricula,
                $registro->nome,
                db_formatar($registro->periodo_aquisitivo_inicial, 'd'),
                db_formatar($registro->periodo_aquisitivo_final, 'd'),
                db_formatar($registro->periodogozoinicial, 'd'),
                db_formatar($registro->periodogozofinal, 'd'),
                $registro->local_trabalho,
                $registro->faltas
            );

            fputs($rArquivo, implode(",", $linha) . "\n");
        }

        fclose($rArquivo);

        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=' . basename($arquivo));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($arquivo));
        readfile($arquivo);
        exit();
    }
    $dadosAgrupados = array();
    foreach ($aDadosRelatorios as $registro) {

        $codigoAgrupamento = 'g';
        if ($oParametros->iQuebraPagina == 1) {
            $codigoAgrupamento = $registro->rh55_codigo.$registro->rh55_instit;
        }
        if (empty($dadosAgrupados[$codigoAgrupamento])) {
            $dadosAgrupados[$codigoAgrupamento] = array();
        }
        $dadosAgrupados[$codigoAgrupamento][] = $registro;
    }

    $head1 = "RESUMO DA FOLHA DE PAGAMENTO ";
    $head5 = "PERÍODO : {$oParametros->iMes} / {$oParametros->iAno}";


    $pdf = new PDF('L');
    $pdf->Open();
    $pdf->SetAutoPageBreak(false);
    $lQuebrarPagina = true;
    $alturaLinha = 6;
    $pdf->setfont('arial','B',8);
    foreach ($dadosAgrupados as $localTrabalho => $ferias) {

        $lQuebrarPagina = true;
        if ($oParametros->iQuebraPagina == 1) {
            $head6 = "Local de Trabalho: ".$ferias[0]->local_trabalho;
        }
        foreach ($ferias as $feriasServidor) {
            if ($pdf->getY() > $pdf->h  - 15 || $lQuebrarPagina) {
                $pdf->setfont('arial','B',8);
                $pdf->AddPage();
                $pdf->Cell(20, $alturaLinha,  'Matrícula', 1, 0, 'C');
                $pdf->Cell(89, $alturaLinha,  'Nome', 1, 0, 'C');
                $pdf->Cell(40, $alturaLinha,  'Período Aquisitivo', 1, 0, 'C');
                $pdf->Cell(40, $alturaLinha,  'Período de Gozo', 1, 0, 'C');
                $pdf->Cell(70, $alturaLinha,  'Local de Trabalho', 1, 0, 'C');
                $pdf->Cell(20, $alturaLinha,  'Faltas', 1, 1, 'C');
                $lQuebrarPagina = false;
            }
            $pdf->setfont('arial','',8);

            $pdf->Cell(20, $alturaLinha,  $feriasServidor->matricula, 1, 0, 'L');
            $pdf->Cell(89, $alturaLinha,  $feriasServidor->nome, 1, 0, 'L');
            $pdf->Cell(40, $alturaLinha,  db_formatar($feriasServidor->periodo_aquisitivo_inicial, 'd') . ' - ' . db_formatar($feriasServidor->periodo_aquisitivo_final, 'd'), 1, 0, 'C');
            $pdf->Cell(40, $alturaLinha,  db_formatar($feriasServidor->periodogozoinicial, 'd') . ' - ' . db_formatar($feriasServidor->periodogozofinal, 'd') , 1, 0, 'C');
            $pdf->Cell(70, $alturaLinha,  $feriasServidor->local_trabalho, 1, 0, 'L');
            $pdf->Cell(20, $alturaLinha,  '', 1, 1, 'C');



        }

    }

    $pdf->Output();

} catch (\Exception $eErro) {

    db_redireciona('db_erros.php?fechar=true&db_erro='. $eErro->getMessage() );
    exit;
}


function sql_query_baseServidores($iMesFolha, $iAnoFolha, $iInstituicao, $sCampos = "", $sWhere = "", $sOrdem = "", $sAgrupamento = "")
{

    if (empty($sCampos)) {
        $sCampos = "*";
    }
    $sSQLBase = "select {$sCampos}                                                                                              \n";
    $sSQLBase .= "  from cadferia ";
    $sSQLBase .= "      inner join rhpessoal  on  r30_regist = rh01_regist ";
    $sSQLBase .= "                           and  r30_anousu =       $iAnoFolha ";
    $sSQLBase .= "                           and  r30_mesusu =       $iMesFolha ";
    $sSQLBase .= "       inner join cgm                  on cgm.z01_numcgm                = rhpessoal.rh01_numcgm                \n";
    $sSQLBase .= "       inner join rhpessoalmov         on rhpessoalmov.rh02_regist      = rhpessoal.rh01_regist                \n";
    $sSQLBase .= "       left  join rhpescargo           on rhpescargo.rh20_seqpes        = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase .= "       left  join rhcargo              on rhcargo.rh04_codigo           = rhpescargo.rh20_cargo                \n";
    $sSQLBase .= "                                      and rhcargo.rh04_instit           = rhpessoalmov.rh02_instit             \n";
    $sSQLBase .= "       left  join rhfuncao             on rhfuncao.rh37_funcao          = rhpessoalmov.rh02_funcao             \n";
    $sSQLBase .= "                                      and rhfuncao.rh37_instit          = rhpessoalmov.rh02_instit             \n";
    $sSQLBase .= "       left  join rhlota               on rhlota.r70_codigo             = rhpessoalmov.rh02_lota               \n";
    $sSQLBase .= "                                      and rhlota.r70_instit             = rhpessoalmov.rh02_instit             \n";
    $sSQLBase .= "       left  join rhlotaexe            on rh26_codigo                   = r70_codigo                           \n";
    $sSQLBase .= "                                      and rh26_anousu                   = rh02_anousu                          \n";
    $sSQLBase .= "       left  join orcorgao             on o40_orgao                     = rh26_orgao                           \n";
    $sSQLBase .= "                                      and o40_anousu                    = rhpessoalmov.rh02_anousu             \n";
    $sSQLBase .= "                                      and o40_instit                    = rhpessoalmov.rh02_instit             \n";
    $sSQLBase .= "       left  join rhlotavinc           on rh25_codigo                   = r70_codigo                           \n";
    $sSQLBase .= "                                      and rh25_anousu                   = rhpessoalmov.rh02_anousu             \n";
    $sSQLBase .= "       left  join orctiporec           on o15_codigo                    = rh25_recurso                         \n";
    $sSQLBase .= "       inner join rhregime             on rhregime.rh30_codreg          = rhpessoalmov.rh02_codreg             \n";
    $sSQLBase .= "                                      and rhregime.rh30_instit          = rhpessoalmov.rh02_instit             \n";
    $sSQLBase .= "       left  join rhpesrescisao        on rhpesrescisao.rh05_seqpes     = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase .= "       left  join rhpespadrao          on rhpespadrao.rh03_seqpes       = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase .= "                                      and rhpespadrao.rh03_anousu       = rhpessoalmov.rh02_anousu             \n";
    $sSQLBase .= "                                      and rhpespadrao.rh03_mesusu       = rhpessoalmov.rh02_mesusu             \n";
    $sSQLBase .= "       left  join padroes              on padroes.r02_anousu            = rhpespadrao.rh03_anousu              \n";
    $sSQLBase .= "                                      and padroes.r02_mesusu            = rhpespadrao.rh03_mesusu              \n";
    $sSQLBase .= "                                      and padroes.r02_regime            = rhpespadrao.rh03_regime              \n";
    $sSQLBase .= "                                      and padroes.r02_codigo            = rhpespadrao.rh03_padrao              \n";
    $sSQLBase .= "                                      and padroes.r02_instit            = rhpessoalmov.rh02_instit             \n";
    $sSQLBase .= "       left  join rhpeslocaltrab       on rhpeslocaltrab.rh56_seqpes    = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase .= "                                      and rhpeslocaltrab.rh56_princ     = 't'                                  \n";
    $sSQLBase .= "       left  join rhlocaltrab          on rhpeslocaltrab.rh56_localtrab = rhlocaltrab.rh55_codigo              \n";
    $sSQLBase .= "       left  join rhpesdoc             on rhpesdoc.rh16_regist          = rhpessoal.rh01_regist                \n";
    $sSQLBase .= "       left  join rhpesbanco           on rhpesbanco.rh44_seqpes        = rhpessoalmov.rh02_seqpes             \n";
    $sSQLBase .= "       left  join (select distinct rhipe.*,                                                                    \n";
    $sSQLBase .= "                          rh01_regist as rh62_regist                                                           \n";
    $sSQLBase .= "                     from rhiperegist                                                                          \n";
    $sSQLBase .= "                          inner join rhipe     on rh14_sequencia = rh62_sequencia                              \n";
    $sSQLBase .= "                          inner join rhpessoal on rh62_regist    = rh01_regist                                 \n";
    $sSQLBase .= "                  ) as rhipe           on rh01_regist                   = rhipe.rh62_regist                    \n";
    $sSQLBase .= "       left  join rhinstrucao          on rhinstrucao.rh21_instru       = rhpessoal.rh01_instru                \n";
    $sSQLBase .= "       left  join rhestcivil           on rhestcivil.rh08_estciv        = rhpessoal.rh01_estciv                \n";

    $sSQLBase .= " where rh02_anousu = $iAnoFolha                                                                                \n";
    $sSQLBase .= "   and rh02_mesusu = $iMesFolha                                                                                \n";
    $sSQLBase .= "   and rh02_instit = $iInstituicao                                                                             \n";
    if (!empty($sWhere)) {
        $sSQLBase .= "   and {$sWhere}                                                                                             \n";
    }
    if (!empty($sAgrupamento)) {
        $sSQLBase .= "group by {$sAgrupamento}";
    }
    if (!empty($sOrdem)) {
        $sSQLBase .= " order by {$sOrdem}                                                                                          \n";
    }

    return $sSQLBase;
}
