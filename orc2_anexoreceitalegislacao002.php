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
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/ReceitaSaldo.php"));


db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoReceitaLegislacao");

$clAssinatura  = new cl_assinatura;
$oGet          = db_utils::postMemory($_GET);
$sInstituicao  = str_replace("-", ",", $oGet->db_selinstit);
$iCodRelatorio = 117;
$iCodPeriodo   = 1;
$iAnoUsu       = db_getsession("DB_anousu");


$formatacaoReceita = EMENTARIO_RECEITA ? 'ementario_receita' : 'receita';

/**
 * Variáveis de Configuração do relatório
 * Tm = Tamanho
 */
$iAlt      = 4;
$iTmFonte  = 6;
$iTmValor  = 16.5; // Tamanho da Coluna de Valores
$iTmDescr  = 45;   // Tamanho da Coluna de Descrição
$iTmEstrut = 28;   // Tamanho da Coluna de Estrutura
$iTmReduz  = 10;   // Tamanho da Coluna de Rec
$iFundo    =  0;
$iTamanhoSubstrDescricao = 80;

/**
 * Monta Sql para buscar as Instituições
 */
$sSqlInstit   = "select codigo,nomeinst, munic from db_config where codigo in ({$sInstituicao}) ";

$rsInstit     = db_query($sSqlInstit);
$oInstit      = db_utils::getCollectionByRecord($rsInstit);

$sCodInstit   = "";
$sNomeInstit  = "";
$sVirgula     = "";
/**
 * Monta uma String com as instituições recebidas
 */
foreach ($oInstit as $key => $value) {

    $sNomeInstit .= $sVirgula.$value->nomeinst;
    $sCodInstit  .= "{$sVirgula}{$value->codigo}";
    $sVirgula     = ", ";
}

$sConsolidadas = "";
if ($oGet->lConsolidado == 1) {
    $sConsolidadas = " - Consolidadas";
}

/**
 * Monta Cabeçalho
 */
$head1 = "MUNICÍPIO DE {$oInstit[0]->munic}";
$head2 = "Quadro Discriminativo das Receitas e suas Respectivas Legislações {$sConsolidadas}";
$head3 = "Leio Orçamentária de {$iAnoUsu}";
$head4 = "INSTITUIÇÕES : ".substr($sNomeInstit, 0, 180);


/**
 * Busca os Balancetes
 */
$oRelatorio    = new AnexoReceitaLegislacao($iAnoUsu, $iCodRelatorio, $iCodPeriodo);
$oRelatorio->setInstituicoes($sCodInstit);

/**
 * Retorna Array com os Dados
 */
$aRelatorio = $oRelatorio->getDados();


/**
 * Começo da estrutura do relatório
 */
// Variável de controle para primeira Página
$lPrimeiraPagina = true;

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(241);
$oPdf->setfont('arial','b',8);
$oPdf->setleftmargin(10);


/**
 * Itera sobre a Collection da Receita imprimindo seu conteúdo
 */
foreach ($aRelatorio as $oReceita) {

    /**
     * Imprime Cabeçalho
     */
    if ($oPdf->gety() > $oPdf->h-30 || $lPrimeiraPagina) {

        $lPrimeiraPagina = false;
        $oPdf->addpage("L");
        $oPdf->setfont('arial','B',8);
        $oPdf->cell(25,  $iAlt, "Código",         "B", 0, "L", 0);
        $oPdf->cell(105, $iAlt, "Descrição",     "B", 0, "L", 0);
        $oPdf->cell(30,  $iAlt,"Valor Estimado", "B", 0, "R", 0);
        $oPdf->cell(119, $iAlt,"Legislação",    "B", 0, "L", 0);

        $oPdf->ln();
    }


    /**
     * Variável de controle para setar negrito quando forconta Sintética
     */
    $sBold   = "";
    /**
     * Variável para identação do fonte
     */
    $sEspaco = "";
    if ($oReceita->codigoReceita == 0) {

        $sBold   = 'B';
        $sEspaco = "";
        $oReceita->codigoReceita = '';
    }

    $sEstrutura = db_formatar($oReceita->estrutural, $formatacaoReceita);
    /**
     * Busca o número de níveis do Estrutural
     */
    $sEspaco    = nivelEstrutura($sEstrutura);

    /**
     * Imprime as linhas do Relatório
     */
    $oPdf->setfont('arial', $sBold,$iTmFonte);
    $oPdf->cell(25,  $iAlt, $sEstrutura,                                  0, 0, "L");
    $oPdf->cell(105, $iAlt, substr($sEspaco.$oReceita->descricao, 0, 80), 0, 0, "L");
    $oPdf->cell(30,  $iAlt, db_formatar($oReceita->valorEstimado,'f'),    0, 0, "R");
    $oPdf->cell(119, $iAlt, substr($oReceita->legislacao, 0, 80),         0, 1, "L");

}
$oPdf->line(10, $oPdf->GetY(), $oPdf->w - 8, $oPdf->GetY());

$oRelatorio->getNotaExplicativa($oPdf, $iCodPeriodo);

$oPdf->Output();


/**
 * Recebe o Nível de um Código Estrutular e identa com espaços
 *
 * @param integer $iNivel
 * @return String
 */
function setIdentacao($iNivel) {

    $sEspaco = "";
    if ($iNivel > 1) {
        $sEspaco = str_repeat("  ", $iNivel);
    }
    return $sEspaco;
}

/**
 * Recebe um código estrutural formatado corretamente e retorna o número de níveis que ele possui
 *
 * @param String $sStrutural
 * @return interger
 */
function nivelEstrutura($sStrutural) {

    $aNiveis = explode(".", $sStrutural);
    $iNivel  = 1;
    foreach ($aNiveis as $iIndice => $sNivel) {

        $iTamanhoNivel = strlen($sNivel);
        if ($sNivel != str_repeat('0', $iTamanhoNivel)){
            $iNivel  = $iIndice+1;
        }
    }
    return setIdentacao($iNivel);
}
