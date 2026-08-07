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
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("classes/db_bens_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("classes/db_db_depart_classe.php"));
require_once(modification("model/itemSolicitacao.model.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("model/configuracao/UsuarioSistema.model.php"));
db_app::import("empenho.AutorizacaoEmpenho");
db_app::import("CgmFactory");
$oGet = db_utils::postMemory($_GET);
$isPB = getEstadoInstituicao() == "PB";


$oUsuarioSistema = new UsuarioSistema(db_getsession("DB_id_usuario"));

$iAnoUsuSessao      = db_getsession("DB_anousu");
$iInstituicaoSessao = db_getsession("DB_instit");

$iSolicitaInicio    = $oGet->iSolicitaInicio;
$iSolicitaFim       = $oGet->iSolicitaFim;

$aWhereSolicitacao = array();
if (!empty($iSolicitaInicio)) {
    $aWhereSolicitacao[] = "pc10_numero >= {$iSolicitaInicio}";
}
if (!empty($iSolicitaFim)) {
    $aWhereSolicitacao[] = "pc10_numero <= {$iSolicitaFim}";
}
if ($isPB) {
    $aWhereSolicitacao[] = "pc10_instit = {$iInstituicaoSessao}";
}

$sWhereSolicitacao = implode(" and ", $aWhereSolicitacao);

$oDaoSolicita      = db_utils::getDao('solicita');
$sSqlBuscaSolicita = $oDaoSolicita->sql_query_solicita(null, "distinct *", null, $sWhereSolicitacao);
$rsBuscaSolicita   = $oDaoSolicita->sql_record($sSqlBuscaSolicita);
$iTotalSolicitacao = $oDaoSolicita->numrows;


if ($iTotalSolicitacao == 0) {
    //db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para o filtro selecionado.");exit;
}

$aDadosImprimir = array();
for ($iRowSolicita = 0; $iRowSolicita < $iTotalSolicitacao; $iRowSolicita++) {
    $oDadoSolicita = db_utils::fieldsMemory($rsBuscaSolicita, $iRowSolicita);
    $oStdDadosSolicita = new stdClass();
    $oStdDadosSolicita->iCodigoSolicitacao = $oDadoSolicita->pc10_numero;
    $oStdDadosSolicita->sResumoSolicitacao = $oDadoSolicita->pc10_resumo;
    $oStdDadosSolicita->aDotacao           = array();

    $oDaoSolicitem  = db_utils::getDao('solicitem');
    $sSqlBuscaItens = $oDaoSolicitem->sql_query_file(null, "*", null, "pc11_numero = {$oDadoSolicita->pc10_numero}");
    $rsBuscaItens   = $oDaoSolicitem->sql_record($sSqlBuscaItens);
    $iTotalItens    = $oDaoSolicitem->numrows;

    if ($iTotalItens > 0) {

        for ($iRowItens = 0; $iRowItens < $iTotalItens; $iRowItens++) {
            $oDadoItem            = db_utils::fieldsMemory($rsBuscaItens, $iRowItens);

            $sCamposDotacao       = "orcorgao.o40_orgao, orcorgao.o40_descr, ";
            $sCamposDotacao      .= "orcunidade.o41_unidade, orcunidade.o41_descr, ";
            $sCamposDotacao      .= "orcfuncao.o52_funcao, orcfuncao.o52_descr, ";
            $sCamposDotacao      .= "orcsubfuncao.o53_subfuncao, orcsubfuncao.o53_descr, ";
            $sCamposDotacao      .= "orcprograma.o54_programa, orcprograma.o54_descr, ";
            $sCamposDotacao      .= "orcprojativ.o55_projativ, orcprojativ.o55_descr, ";
            $sCamposDotacao      .= "orcelemento.o56_elemento, orcelemento.o56_descr, ";
            $sCamposDotacao      .= "orctiporec.o15_codigo,orctiporec.o15_descr, ";
            $sCamposDotacao      .= "orctiporec.o15_recurso, orctiporec.o15_complemento, ";
            $sCamposDotacao      .= "orcdotacao.o58_coddot, orcreserva.o80_valor, orcreserva.o80_dtlanc,";
            $sCamposDotacao      .= "pcdotac.pc13_anousu, pcdotac.pc13_valor";

            $sWhereItem           = "pcdotac.pc13_codigo = {$oDadoItem->pc11_codigo}";
            $oDaoProcessoCompra   = db_utils::getDao('pcdotac');
            $sSqlDotacaoPorItem   = $oDaoProcessoCompra->sql_query_dotacao(
                null,
                null,
                null,
                $sCamposDotacao,
                null,
                $sWhereItem
            );
            $rsDotacaoPorItem     = $oDaoProcessoCompra->sql_record($sSqlDotacaoPorItem);
            $iTotalDotacaoPorItem = $oDaoProcessoCompra->numrows;
            if ($iTotalDotacaoPorItem > 0) {
                for ($iRowDotacao = 0; $iRowDotacao < $iTotalDotacaoPorItem; $iRowDotacao++) {
                    $oDadosDotacao = db_utils::fieldsMemory($rsDotacaoPorItem, $iRowDotacao);
                    $oDotacao      = new Dotacao($oDadosDotacao->o58_coddot, $oDadosDotacao->pc13_anousu);

                    if (!array_key_exists($oDadosDotacao->o58_coddot, $oStdDadosSolicita->aDotacao)) {
                        $oStdDotacao   = new stdClass();
                        $oStdDotacao->iCodigoOrgao               = $oDadosDotacao->o40_orgao;
                        $oStdDotacao->sDescricaoOrgao            = $oDadosDotacao->o40_descr;
                        $oStdDotacao->iCodigoUnidade             = $oDadosDotacao->o41_unidade;
                        $oStdDotacao->sDescricaoUnidade          = $oDadosDotacao->o41_descr;
                        if (isParaiba()) {
                            $sql = "select z01_nomecomple from sagresresponsavelunidadeorcamentaria
                            inner join cgm on c140_cgm = z01_numcgm where c140_orgao = {$oDadosDotacao->o40_orgao}
                            and c140_unidade = {$oDadosDotacao->o41_unidade} and c140_anousu = {$iAnoUsuSessao}
                             and c140_ativo = 't'";
                            $rsResponsavelUnidade = db_query($sql);
                            $oResponsavelUnidade = db_utils::fieldsMemory($rsResponsavelUnidade, 0);
                            $oStdDotacao->nomeResponsavelUnidade = $oResponsavelUnidade->z01_nomecomple;
                        }
                        $oStdDotacao->iCodigoFuncao              = $oDadosDotacao->o52_funcao;
                        $oStdDotacao->sDescricaoFuncao           = $oDadosDotacao->o52_descr;
                        $oStdDotacao->iCodigoSubFuncao           = $oDadosDotacao->o53_subfuncao;
                        $oStdDotacao->sDescricaoSubFuncao        = $oDadosDotacao->o53_descr;
                        $oStdDotacao->iCodigoPrograma            = $oDadosDotacao->o54_programa;
                        $oStdDotacao->sDescricaoPrograma         = $oDadosDotacao->o54_descr;
                        $oStdDotacao->iCodigoProjetoAtividade    = $oDadosDotacao->o55_projativ;
                        $oStdDotacao->sDescricaoProjetoAtividade = $oDadosDotacao->o55_descr;
                        $oStdDotacao->iCodigoElemento            = $oDadosDotacao->o56_elemento;
                        $oStdDotacao->sDescricaoElemento         = $oDadosDotacao->o56_descr;
                        $oStdDotacao->iCodigoRecurso             = $oDadosDotacao->o15_codigo;
                        $oStdDotacao->iRecursoCod                = $oDadosDotacao->o15_recurso;
                        $oStdDotacao->sDescricaoRecurso          = $oDadosDotacao->o15_descr;
                        $oStdDotacao->iCodigoDotacao             = $oDadosDotacao->o58_coddot;
                        $oStdDotacao->iCodigoComplemento         = $oDadosDotacao->o15_complemento;
                        $rsPegarComplemento = db_query("select * from complementofonterecurso
                                            where o200_sequencial = {$oDadosDotacao->o15_complemento}");
                        $oPegaComplemento = db_utils::fieldsMemory($rsPegarComplemento, 0);
                        $oStdDotacao->Complemento                = $oPegaComplemento->o200_descricao;
                        $oStdDotacao->aDadosTabela               = array();

                        $oStdDadosTabela                          = new stdClass();
                        $oStdDadosTabela->nValorReserva           = $oDadosDotacao->o80_valor;
                        $oStdDadosTabela->dtReserva               = $oDadosDotacao->o80_dtlanc;
                        $oStdDadosTabela->sProcessoAdministrativo = $oDadoSolicita->pc90_numeroprocesso;
                        $oStdDadosTabela->nSaldoDotacaoAntes      = $oDotacao->getSaldoAtual();
                        $oStdDadosTabela->nSaldoFinal             = $oDotacao->getSaldoFinal();
                        $oStdDadosTabela->nSaldoDotacaoAtual      = $oDotacao->getSaldoAtualMenosReservado();
                        $oStdDadosTabela->nValorTotalItens        = $oDadosDotacao->pc13_valor;
                        $oStdDotacao->aDadosTabela[$oDadosDotacao->o80_dtlanc]   = $oStdDadosTabela;
                        $oStdDadosSolicita->aDotacao[$oDadosDotacao->o58_coddot] = $oStdDotacao;
                    } else {
                        if (array_key_exists(
                            $oDadosDotacao->o80_dtlanc,
                            $oStdDadosSolicita->aDotacao[$oDadosDotacao->o58_coddot]->aDadosTabela
                        )) {
                            $oStdDadosSolicita->aDotacao[$oDadosDotacao->o58_coddot]
                                ->aDadosTabela[$oDadosDotacao->o80_dtlanc]->nValorReserva += $oDadosDotacao->o80_valor;
                            $oStdDadosSolicita->aDotacao[$oDadosDotacao->o58_coddot]
                                ->aDadosTabela[$oDadosDotacao->o80_dtlanc]->nValorTotalItens += $oDadosDotacao->pc13_valor;
                        } else {
                            $oStdDadosTabela                          = new stdClass();
                            $oStdDadosTabela->nValorTotalItens        = $oDadosDotacao->pc13_valor;
                            $oStdDadosTabela->nValorReserva           = $oDadosDotacao->o80_valor;
                            $oStdDadosTabela->dtReserva               = $oDadosDotacao->o80_dtlanc;
                            $oStdDadosTabela->sProcessoAdministrativo = $oDadoSolicita->pc90_numeroprocesso;
                            $oStdDadosTabela->nSaldoDotacaoAntes      = $oDotacao->getSaldoAtual();
                            $oStdDadosTabela->nSaldoFinal             = $oDotacao->getSaldoFinal();
                            $oStdDadosTabela->nSaldoDotacaoAtual      = $oDotacao->getSaldoAtualMenosReservado();
                            $oStdDadosSolicita->aDotacao[$oDadosDotacao->o58_coddot]
                                ->aDadosTabela[$oDadosDotacao->o80_dtlanc] = $oStdDadosTabela;
                        }
                    }
                }
            }
        }
        $aDadosImprimir[] = $oStdDadosSolicita;
    }
}

$iCodigoSolicitacao = $aDadosImprimir[0]->iCodigoSolicitacao;
if ($isPB) {
    $head1 = PHP_EOL."DEMONSTRATIVO DA PREVISÃO DE DOTAÇÃO ORÇAMENTÁRIA E DECLARAÇÃO.";
//    $head2 = "Reserva Orçamentária: Nº ".(string)$iCodigoSolicitacao;
} else {
    $head2 = "Nota de Bloqueio";
}
$head3 = "Exercício de {$iAnoUsuSessao}";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->SetFont('arial', 'b', 6);

$iAlturaLinha       = 4;
$lPrimeiroLaco      = true;

foreach ($aDadosImprimir as $iIndice => $oSolicitacao) {
    if ($oPdf->gety() > $oPdf->h-35 || $lPrimeiroLaco || $iCodigoSolicitacao != $oSolicitacao->iCodigoSolicitacao) {
        imprimeDetalhamentoCabecalho($oPdf, $oSolicitacao, $iAlturaLinha);
        $lPrimeiroLaco = false;
    }
    foreach ($oSolicitacao->aDotacao as $oDadosDotacao) {
        if ($oPdf->gety() > $oPdf->h-10 || $lPrimeiroLaco) {
            $oPdf->AddPage();
            imprimeDetalhamentoCabecalho($oPdf, $oSolicitacao, $iAlturaLinha);
        }
        imprimeDetalhamento($oPdf, $oDadosDotacao, $iAlturaLinha, $oSolicitacao) ;
    }
    $oDaoAssinatura = new cl_assinatura();
    $sAssinatura    = $oDaoAssinatura->assinatura(1900);
    $oPdf->ln(10);
    if ($isPB) {
        $sDescricaoUnidadeSemAcento  = preg_replace(
            array("/(á|à|ã|â|ä)/",
                "/(Á|À|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/",
                "/(Ñ)/"),
            explode(" ", "a A e E i I o O u U n N"),
            $oStdDotacao->sDescricaoUnidade
        );
        $sAssinatura = $oStdDotacao->nomeResponsavelUnidade;
        $oPdf->setfont('Arial', '', 7);

        $mesPorExtenso = ["01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho",
            "07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro"];
        $dataSeparada = explode("-", $oStdDadosTabela->dtReserva);
        if ((($oStdDadosTabela->nSaldoFinal) + ($oStdDadosTabela->nValorReserva)) >= floatval($oStdDadosTabela->nValorTotalItens)) {
            $oPdf->multicell(
                160,
                $iAlturaLinha,
                "Declaro para os devidos fins, que a geração de despesa, referente objeto"
                . " acima descrito, tem  adequação orçamentária e financeira com a Lei" . PHP_EOL
                . "Orçamentária Anual (LOA) e compatibilidade com o Plano Plurianual (PPA),"
                . " como também, com a Lei de Diretrizes Orçamentárias (LDO).",
                0,
                "J"
            );
        } else {
            $oPdf->multicell(
                160,
                $iAlturaLinha,
                "Declaro para os devidos fins, que a geração de despesa, referente objeto"
                . " acima descrito, tem  adequação orçamentária e financeira com a Lei" . PHP_EOL
                . "Orçamentária Anual (LOA) e compatibilidade com o Plano Plurianual (PPA),"
                . " como também, com a Lei de Diretrizes Orçamentárias (LDO)."
                . "A dotação orçamentária poderá ser suplementada quando da execução.",
                0,
                "J"
            );
        }
        $oPdf->ln();
        $oPdf->cell(
            0,
            $iAlturaLinha,
            ucwords(strtolower(InstituicaoRepository::getInstituicaoPrefeitura()->getMunicipio()))
            .", ".$dataSeparada[2]." de ".$mesPorExtenso[$dataSeparada[1]]." de ".$dataSeparada[0],
            0,
            1,
            "C",
            0
        );
    }
    $oPdf->setfont('Arial', '', 6);


    if ($isPB) {
        $oPdf->ln();
        $oPdf->setfont('Arial', 'B', 7);
        $oPdf->cell(0, $iAlturaLinha, "{$sAssinatura}", "", 1, "C", 0);
        $oPdf->setfont('Arial', '', 6);
        $oPdf->cell(0, $iAlturaLinha, $sDescricaoUnidadeSemAcento, "", 1, "C", 0);
    } else {
        $oPdf->cell(95, $iAlturaLinha, "{$sAssinatura}", "", 1, "C", 0);
        $oPdf->cell(95, $iAlturaLinha, "Emitente", "", 0, "C", 0);
        $oPdf->cell(95, $iAlturaLinha, "{$oUsuarioSistema->getNome()}", "", 0, "C", 0);
        $oPdf->cell(95, $iAlturaLinha, "Secretário Municipal do Planejamento", "", 1, "C", 0);
    }
}

$oPdf->Output();


function imprimeDetalhamentoCabecalho($oPdf, $oDado, $iAlturaLinha)
{

    $oPdf->AddPage();
    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell(200, $iAlturaLinha, "Solicitação de Compras nº: {$oDado->iCodigoSolicitacao}", 0, 1, "L", 0);
    $isPB = getEstadoInstituicao() == "PB";
    if (!$isPB) {
        $oPdf->multicell(200, $iAlturaLinha, "Histórico : {$oDado->sResumoSolicitacao}");
        $oPdf->cell(
            190,
            $iAlturaLinha,
            "Informamos que o saldo da dotação encontra-se suficiente e já foi bloqueado, conforme descrito abaixo:",
            0,
            1,
            "L",
            0
        );
    } else {
        $oPdf->multicell(0, $iAlturaLinha, "Objeto : {$oDado->sResumoSolicitacao}");
        $oPdf->ln(2);
    }
}

function imprimeDetalhamento($oPdf, $oDado, $iAlturaLinha, $oSolicitacao)
{

    $isPB = getEstadoInstituicao() == "PB";
    if ($oPdf->gety() > $oPdf->h-50) {
        $oDado->iCodigoSolicitacao =
            imprimeDetalhamentoCabecalho($oPdf, $oSolicitacao, $iAlturaLinha);
    }
    $iColuna1 = 20;
    $iColuna2 = 30;
    $iColuna3 = 40;
    $oPdf->ln();
    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell($iColuna1, $iAlturaLinha, "Órgão:", "", 0, "L", 0);
    $oPdf->setfont('Arial', '', 6);
    $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoOrgao}", "", 0, "L", 0);
    $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoOrgao}", "", 1, "L", 0);

    if ($isPB) {
        $oPdf->setfont('Arial', 'B', 6);
        $oPdf->cell($iColuna1, $iAlturaLinha, "Unidade:", "", 0, "L", 0);
        $oPdf->setfont('Arial', '', 6);
        $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoUnidade}", "", 0, "L", 0);
        $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoUnidade}", "", 1, "L", 0);
    } else {
        $oPdf->setfont('Arial', 'B', 6);
        $oPdf->cell($iColuna1, $iAlturaLinha, "Unidade:", "", 0, "L", 0);
        $oPdf->setfont('Arial', '', 6);
        $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoOrgao}{$oDado->iCodigoUnidade}", "", 0, "L", 0);
        $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoUnidade}", "", 1, "L", 0);
    }
    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell($iColuna1, $iAlturaLinha, "Função:", "", 0, "L", 0);
    $oPdf->setfont('Arial', '', 6);
    $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoFuncao}", "", 0, "L", 0);
    $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoFuncao}", "", 1, "L", 0);

    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell($iColuna1, $iAlturaLinha, "Subfunção:", "", 0, "L", 0);
    $oPdf->setfont('Arial', '', 6);
    $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoSubFuncao}", "", 0, "L", 0);
    $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoSubFuncao}", "", 1, "L", 0);

    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell($iColuna1, $iAlturaLinha, "Programa:", "", 0, "L", 0);
    $oPdf->setfont('Arial', '', 6);
    $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoPrograma}", "", 0, "L", 0);
    $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoPrograma}", "", 1, "L", 0);

    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell($iColuna1, $iAlturaLinha, "Projeto/Atividade:", "", 0, "L", 0);
    $oPdf->setfont('Arial', '', 6);
    $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoProjetoAtividade}", "", 0, "L", 0);
    $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoProjetoAtividade}", "", 1, "L", 0);

    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell($iColuna1, $iAlturaLinha, "Elemento:", "", 0, "L", 0);
    $oPdf->setfont('Arial', '', 6);
    $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoElemento}", "", 0, "L", 0);
    $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoElemento}", "", 1, "L", 0);

    if ($isPB) {
        $oPdf->setfont('Arial', 'B', 6);
        $oPdf->cell($iColuna1, $iAlturaLinha, "Recurso:", "", 0, "L", 0);
        $oPdf->setfont('Arial', '', 6);
        $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iRecursoCod}", "", 0, "L", 0);
        $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoRecurso}", "", 1, "L", 0);
        $oPdf->setfont('Arial', 'B', 6);
        $oPdf->cell($iColuna1, $iAlturaLinha, "Complemento:", "", 0, "L", 0);
        $oPdf->setfont('Arial', '', 6);
        $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoComplemento}", "", 0, "L", 0);
        $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->Complemento}", "", 1, "L", 0);
    } else {
        $oPdf->setfont('Arial', 'B', 6);
        $oPdf->cell($iColuna1, $iAlturaLinha, "Recurso:", "", 0, "L", 0);
        $oPdf->setfont('Arial', '', 6);
        $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoRecurso}", "", 0, "L", 0);
        $oPdf->cell($iColuna3, $iAlturaLinha, "{$oDado->sDescricaoRecurso}", "", 1, "L", 0);
    }

    $oPdf->setfont('Arial', 'B', 6);
    $oPdf->cell($iColuna1, $iAlturaLinha, "Código Reduzido:", "", 0, "L", 0);
    $oPdf->setfont('Arial', '', 6);
    $oPdf->cell($iColuna2, $iAlturaLinha, "{$oDado->iCodigoDotacao} ", "", 1, "L", 0);

    $isPB = getEstadoInstituicao() == "PB";
    if ($isPB) {
        $oPdf->ln();
        $oPdf->setfont('Arial', 'B', 6);


        foreach ($oDado->aDadosTabela as $oDadoTabela) {
            $valorTotalDaDotacao = ($oDadoTabela->nSaldoFinal) + ($oDadoTabela->nValorReserva);
            $valorTotalDosItens = floatval($oDadoTabela->nValorTotalItens);
            $oPdf->cell(30, $iAlturaLinha, "Data Bloqueio", 1, 0, "C", 0);
            //SE O SALDO DOTAÇÃO FOR MAIOR OU IGUAL AO O TOTAL DE ITENS
            if (floatval($valorTotalDaDotacao) >= $valorTotalDosItens) {
                if (floatval($oDadoTabela->nValorReserva) != $valorTotalDosItens) {
                    $oPdf->cell(40, $iAlturaLinha, "Valor Solicitado", 1, 0, "C", 0);
                } else {
                    $oPdf->cell(40, $iAlturaLinha, "Valor Reservado", 1, 0, "C", 0);
                }
                $oPdf->cell(50, $iAlturaLinha, "Saldo", 1, 1, "C", 0);
                $oPdf->setfont('Arial', '', 6);
                $oPdf->cell(30, $iAlturaLinha, db_formatar($oDadoTabela->dtReserva, 'd'), 1, 0, "C", 0);
                $oPdf->cell(40, $iAlturaLinha, db_formatar($oDadoTabela->nValorTotalItens, 'f'), 1, 0, "R", 0);
                $oPdf->cell(
                    50,
                    $iAlturaLinha,
                    db_formatar($oDadoTabela->nSaldoFinal + $oDadoTabela->nValorReserva, 'f'),
                    1,
                    1,
                    "R",
                    0
                );
            }
            //SE O SALDO DOTAÇÃO FOR MENOR AO TOTAL DE ITENS
            else {
                $oPdf->cell(40, $iAlturaLinha, "Valor Despesa Solicitado", 1, 0, "C", 0);
                $oPdf->ln();
                $oPdf->setfont('Arial', '', 6);
                $oPdf->cell(30, $iAlturaLinha, db_formatar($oDadoTabela->dtReserva, 'd'), 1, 0, "C", 0);
                $oPdf->cell(40, $iAlturaLinha, db_formatar($oDadoTabela->nValorTotalItens, 'f'), 1, 0, "R", 0);
            }
        }
    } else {
        $oPdf->ln();
        $oPdf->setfont('Arial', 'B', 6);
        $oPdf->cell(30, $iAlturaLinha, "Data Bloqueio", 1, 0, "C", 0);
        $oPdf->cell(40, $iAlturaLinha, "Processo Administrativo", 1, 0, "C", 0);
        $oPdf->cell(40, $iAlturaLinha, "Saldo da Dotação", 1, 0, "C", 0);
        $oPdf->cell(40, $iAlturaLinha, "Valor Bloqueado", 1, 0, "C", 0);
        $oPdf->cell(40, $iAlturaLinha, "Saldo Atual", 1, 1, "C", 0);

        foreach ($oDado->aDadosTabela as $oDadoTabela) {
            $oPdf->setfont('Arial', '', 6);
            $oPdf->cell(30, $iAlturaLinha, db_formatar($oDadoTabela->dtReserva, 'd'), 1, 0, "C", 0);
            $oPdf->cell(40, $iAlturaLinha, $oDadoTabela->sProcessoAdministrativo, 1, 0, "C", 0);
            $oPdf->cell(40, $iAlturaLinha, db_formatar($oDadoTabela->nSaldoDotacaoAntes, 'f'), 1, 0, "R", 0);
            $oPdf->cell(40, $iAlturaLinha, db_formatar($oDadoTabela->nValorReserva, 'f'), 1, 0, "R", 0);
            $oPdf->cell(40, $iAlturaLinha, db_formatar($oDadoTabela->nSaldoDotacaoAtual, 'f'), 1, 1, "R", 0);
        }
    }
}
