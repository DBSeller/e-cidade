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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("fpdf151/pdf.php"));

$oGet = db_utils::postMemory($_GET);

$sData = date("Ymd", db_getsession('DB_datausu'));

$oGet->analitico = ($oGet->tipoemissao == "a" ? true : false);

$competencia = DBCompetencia::createFromString($oGet->competencia);
$sArquivoCSV = "tmp/MSC_{$competencia->getAno()}_{$competencia->getMes()}_{$sData}.csv";
$encerramento = $oGet->encerramento == 's';

$mesComparacao = $competencia->getMes();
if ($encerramento && $competencia->getMes() == 12) {
    $mesComparacao = 13;
}

$where = " c132_ano = {$competencia->getAno()} and c132_mes = {$mesComparacao}";
if ($oGet->estruturais != '') {
    $aEstrutural = explode(",", $oGet->estruturais);
    $aWhereEstrutural = array();
    foreach ($aEstrutural as $sEstrutural) {
        $sEstrutural = trim($sEstrutural);
        if (empty($sEstrutural)) {
            continue;
        }
        $aWhereEstrutural[] = " c133_estrutural like '{$sEstrutural}%' ";
    }
    if ($aWhereEstrutural) {
        $where .= " and (" . implode(" OR ", $aWhereEstrutural) . ") ";
    }
}

$sql = "select c133_estrutural as estrutural, ";
$sql .= "      c133_atributos as atributos, ";
$sql .= "      (case when c133_natureza = 'C' then c133_beginning_balance * -1";
$sql .= "            else c133_beginning_balance end) as beginning_balance, ";
$sql .= "      c133_period_change_debit as period_change_debit, ";
$sql .= "      c133_period_change_credit period_change_credit, ";
$sql .= "      (case when c133_natureza_final = 'C' then c133_ending_balance * -1 ";
$sql .= "            else c133_ending_balance end) as ending_balance ";
$sql .= " from contabilidade.matriz_saldo_contabil_lancamentos ";
$sql .= "      inner join contabilidade.matriz_saldo_contabil on c132_sequencial = c133_matriz_saldo_contabil ";
$sql .= " where {$where}";
$sql .= " order by c133_estrutural, c133_atributos";

$contas = array();
$dados = db_utils::makeCollectionFromRecord(db_query($sql), function ($dados) use (&$contas) {

    if (empty($contas[$dados->estrutural])) {
        $dadosConta = new \stdClass();
        $dadosConta->estrutural = $dados->estrutural;
        $dadosConta->beginning_balance = 0;
        $dadosConta->period_change_debit = 0;
        $dadosConta->period_change_credit = 0;
        $dadosConta->ending_balance = 0;
        $dadosConta->atributos = array();
        $contas[$dados->estrutural] = $dadosConta;
    }
    $dadosConta = $contas[$dados->estrutural];
    $dadosConta->beginning_balance += $dados->beginning_balance;
    $dadosConta->period_change_debit += $dados->period_change_debit;
    $dadosConta->period_change_credit += $dados->period_change_credit;
    $dadosConta->ending_balance += $dados->ending_balance;
    $dadosConta->atributos[] = $dados;

});


switch ($oGet->formato) {

    case 'pdf':
        emitirPdf($contas, $oGet, $encerramento);
        break;
    case 'csv':
        emitirCsv($sArquivoCSV, $contas, $oGet);
        break;
}

function emitirPdf($dados, $parametros, $encerramento)
{

    $encerra = $encerramento ? 'Sim' : 'Não';
    global $head2, $head4, $head5;
    $head2 = "Balacente Da Matriz de Saldos Contábeis";
    $head4 = "Competência: {$parametros->competencia}";
    $head5 = "Encerramento: {$encerra}";
    $oPdf = new PDF();
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->setfillcolor(235);
    $oPdf->AddPage("L");
    $oPdf->ln(2);
    $tamanhofonte = 10;
    $alturaLinha = 5;
    cabecalho($oPdf, $tamanhofonte, $alturaLinha);
    $resumo = resumo($dados);

    foreach ($dados as $conta) {
        if ($oPdf->h - 25 < $oPdf->getY()) {
            cabecalho($oPdf, $tamanhofonte, $alturaLinha);
        }

        $oPdf->cell(35, $alturaLinha, $conta->estrutural, "TB", 0, "C", 0);
        $oPdf->cell(105, $alturaLinha, '', "TB", 0, "C", 0);
        $oPdf->cell(34, $alturaLinha, db_formatar($conta->beginning_balance, 'f'), "TBLR", 0, "R", 0);
        $oPdf->cell(34, $alturaLinha, db_formatar($conta->period_change_debit, 'f'), "TBLR", 0, "R", 0);
        $oPdf->cell(34, $alturaLinha, db_formatar($conta->period_change_credit, 'f'), "TBLR", 0, "R", 0);
        $oPdf->cell(34, $alturaLinha, db_formatar($conta->ending_balance, 'f'), "TBL", 1, "R", 0);
        foreach ($conta->atributos as $atributos) {
            if ($parametros->analitico) {
                if ($oPdf->h - 25 < $oPdf->getY()) {
                    cabecalho($oPdf, $tamanhofonte, $alturaLinha);
                }
                $listaAtributos = formatarAtributos($atributos->atributos);
                $oPdf->cell(35, $alturaLinha, '', "TB", 0, "C", 0);
                $oPdf->cell(105, $alturaLinha, $listaAtributos, "TBR", 0, "C", 0);
                $oPdf->cell(34, $alturaLinha, db_formatar($atributos->beginning_balance, 'f'), "TBLR", 0, "R", 0);
                $oPdf->cell(34, $alturaLinha, db_formatar($atributos->period_change_debit, 'f'), "TBLR", 0, "R", 0);
                $oPdf->cell(34, $alturaLinha, db_formatar($atributos->period_change_credit, 'f'), "TBLR", 0, "R", 0);
                $oPdf->cell(34, $alturaLinha, db_formatar($atributos->ending_balance, 'f'), "TBL", 1, "R", 0);
            }
        }
    }
    $oPdf->ln(3);
    $oPdf->SetFont('Arial', 'b', 12);
    $oPdf->cell(35, $alturaLinha, "Nível", "TB", 0, "C", 0);
    $oPdf->cell(105, $alturaLinha, "Descrição", "TBR", 0, "C", 0);
    $oPdf->cell(34, $alturaLinha, "Saldo Inicial", "TBL", 0, "C", 0);
    $oPdf->cell(34, $alturaLinha, "Débitos", "TBL", 0, "C", 0);
    $oPdf->cell(34, $alturaLinha, "Créditos", "TBL", 0, "C", 0);
    $oPdf->cell(34, $alturaLinha, "Saldo Final", "TBL", 1, "C", 0);
    $oPdf->SetFont('Arial', '', 10);

    foreach ($resumo as $indice => $valor) {
        $oPdf->cell(35, $alturaLinha, $indice, "TB", 0, "C", 0);
        $oPdf->cell(105, $alturaLinha, $valor["descricao"], "TBR", 0, "L", 0);
        $oPdf->cell(34, $alturaLinha, db_formatar($valor["beginning_balance"], 'f'), "TBL", 0, "R", 0);
        $oPdf->cell(34, $alturaLinha, db_formatar($valor["period_change_debit"], 'f'), "TBL", 0, "R", 0);
        $oPdf->cell(34, $alturaLinha, db_formatar($valor["period_change_credit"], 'f'), "TBL", 0, "R", 0);
        $oPdf->cell(34, $alturaLinha, db_formatar($valor["ending_balance"], 'f'), "TBL", 1, "R", 0);
    }

    $oPdf->Output();
}

/**
 * Formata a lista de atributos
 * @param $atributos
 * @return string
 */
function formatarAtributos($atributos)
{
    $listaAtributos = explode("|", $atributos);
    $retorno = array_map(function ($atributo) {
        $dadosAtributos = explode('#', $atributo);
        return $dadosAtributos[1] . ':' . $dadosAtributos[0];
    }, $listaAtributos);
    return implode(", ", $retorno);
}

function resumo($contas)
{

    $descricoes = array(
        1 => "Total do Ativo",
        2 => "Total do Passivo",
        3 => "Total das Variações Patrimoniais Diminutivas",
        4 => "Total das Variações Patrimoniais Aumentativas",
        5 => "Total dos Controles da Aprovação do Planejamentoe Orçamento",
        6 => "Total dos Controles da Execução do Planejamento e Orçamento",
        7 => "Total dos Controles Devedores",
        8 => "Total dos Controles Credores"
    );
    foreach (range(1, 8) as $nivel) {
        $totalizador[$nivel] = array(
                'descricao' => $descricoes[$nivel],
                'beginning_balance' => 0,
                'period_change_debit' => 0,
                'period_change_credit' => 0,
                'ending_balance' => 0,
        );
    }
    foreach ($contas as $conta) {
        $nivel1 = substr($conta->estrutural, 0, 1);
        $totalizador[$nivel1]["beginning_balance"] += $conta->beginning_balance;
        $totalizador[$nivel1]["period_change_debit"] += $conta->period_change_debit;
        $totalizador[$nivel1]["period_change_credit"] += $conta->period_change_credit;
        $totalizador[$nivel1]["ending_balance"] += $conta->ending_balance;
    }
    return $totalizador;
}

function cabecalho(PDF $pdf, $tamanhofonte, $alturaLinha)
{

    $pdf->SetFont('Arial', 'b', 12);
    $pdf->cell(35, $alturaLinha, 'Conta', "TBR", 0, "C");
    $pdf->cell(105, $alturaLinha, 'Informações Complementares', "TBL", 0, "C");
    $pdf->cell(34, $alturaLinha, 'Saldo Inicial', "TBLR", 0, "C");
    $pdf->cell(34, $alturaLinha, 'Débitos', "TBLR", 0, "C");
    $pdf->cell(34, $alturaLinha, 'Créditos', "TBLR", 0, "C");
    $pdf->cell(34, $alturaLinha, 'Saldo Final', "TBL", 1, "C");
    $pdf->SetFont('Arial', '', $tamanhofonte);
}

function emitirCsv($sArquivoCSV, $dados, $parametros)
{


    $resumo = resumo($dados);
    $conteudo = "";


    $aCabecalho = array(
        "Conta",
        "Informações Complementares",
        "Saldo Inicial",
        "Débitos",
        "Créditos",
        "Saldo Final"
    );

    $linha = implode(";", $aCabecalho) . "\n";
    $conteudo .= $linha;

    foreach ($dados as $conta) {
        $aLinha = array(
            trim($conta->estrutural),
            "",
            trim(db_formatar($conta->beginning_balance, 'f')),
            trim(db_formatar($conta->period_change_debit, 'f')),
            trim(db_formatar($conta->period_change_credit, 'f')),
            trim(db_formatar($conta->ending_balance, 'f'))
        );
        $linha = implode(";", $aLinha) . "\n";
        $conteudo .= $linha;

        foreach ($conta->atributos as $atributos) {
            if ($parametros->analitico) {
                $listaAtributos = formatarAtributos($atributos->atributos);
                $aLinha = array(
                    '',
                    trim($listaAtributos),
                    trim(db_formatar($atributos->beginning_balance, 'f')),
                    trim(db_formatar($atributos->period_change_debit, 'f')),
                    trim(db_formatar($atributos->period_change_credit, 'f')),
                    trim(db_formatar($atributos->ending_balance, 'f'))
                );
                $linha = implode(";", $aLinha) . "\n";
                $conteudo .= $linha;
            }
        }
    }


    $conteudo .= "\n";
    $aCabecalho = array(
        "Nível",
        "Descrição",
        "Saldo Final",
        "Débitos",
        "Créditos",
        "Saldo Final"
    );
    $linha = implode(";", $aCabecalho) . "\n";
    $conteudo .= $linha;
    foreach ($resumo as $indice => $valor) {
        $aLinha = array(
            $indice,
            $valor['descricao'],
            trim(db_formatar($valor['beginning_balance'], 'f')),
            trim(db_formatar($valor['period_change_debit'], 'f')),
            trim(db_formatar($valor['period_change_credit'], 'f')),
            trim(db_formatar($valor['ending_balance'], 'f'))
        );
        $linha = implode(";", $aLinha) . "\n";
        $conteudo .= $linha;
    }

    file_put_contents($sArquivoCSV, $conteudo);
    db_redireciona($sArquivoCSV);

}
