<?php
/**
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("fpdf151/pdf.php"));

use ECidade\RecursosHumanos\ESocial\Repository\ImportacaoQualificacaoCadastral;

try {

    $get = db_utils::postmemory($_GET);

    if (empty($get->arquivo)) {
        throw new ParameterException("Código do arquivo de importação não informado.");
    }

    \db_inicio_transacao();
    $importacaoRepository = new ImportacaoQualificacaoCadastral();
    $qualificacoesCadastrais = $importacaoRepository->getDadosRelatorio($get->arquivo, $get->cargo, $get->lotacao, $get->listaServidores);
    \db_fim_transacao();

    $pdf = new \pdf('L');
    $head1 = "RELATÓRIO DE QUALIFICAÇÃO CADASTRAL";
    $parametrosInformados = array();

    $head3 = "Cargo: ";
    $head3 .= empty($get->descricaoCargo) ? "Todos." : utf8_decode($get->descricaoCargo);
    $head4 = "Lotação: ";
    $head4 .= empty($get->descricaoLotacao) ? "Todas." : utf8_decode($get->descricaoLotacao);
    $head5 = "Listar servidores: ";
    $head5 .= empty($get->descricaoListarServidores) ? "Todos." : utf8_decode($get->descricaoListarServidores);
    $head6 = "Data de emissão: " . date('d/m/Y');
    

    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->SetFillColor(230);
    $pdf->SetAutoPageBreak(false);
   
    imprimirCabecalho($pdf);
    
    $preencheLinha = true;
    foreach ($qualificacoesCadastrais as $index => $qualificacaoCadastral) {
        $preencheLinha = !$preencheLinha;
       

        $inconsistencias = implode("\n", $qualificacaoCadastral->getInconsistencias());
        $quantidadeLinhas = $pdf->NbLines(150,$inconsistencias) * 4;

        if ( $pdf->GetY() >= $pdf->h - ($quantidadeLinhas + 30) ) {
            imprimirCabecalho($pdf);
        }

        $pdf->Cell(15, $quantidadeLinhas, $qualificacaoCadastral->getMatricula(), 0, 0, 'L', $preencheLinha);
        $pdf->Cell(65, $quantidadeLinhas, $qualificacaoCadastral->getNome(), 0, 0, 'L', $preencheLinha);
        $pdf->Cell(45, $quantidadeLinhas, $qualificacaoCadastral->getDescricaoLotacao(), 0, 0, 'L', $preencheLinha);
        $pdf->MultiCell(150, 4, $inconsistencias, 0, 'L', $preencheLinha);
    }

    $pdf->output();


} catch (Exception $e) {
    $mensagem = urlencode($e->getMessage());
    db_redireciona('db_erros.php?fechar=true&db_erro=' . $mensagem);
}

function imprimirCabecalho($pdf)
{
    $pdf->AddPage();
    $pdf->SetFont('arial', 'b', 6);
    $pdf->Cell(15, 4, "MATRÍCULA", 1, 0, 'C');
    $pdf->Cell(65, 4, "NOME", 1, 0, 'C');
    $pdf->Cell(45, 4, "LOTAÇÃO", 1, 0, 'C');
    $pdf->Cell(150, 4, "INCONSISTÊNCIA(s)", 1, 1, 'C');   
    $pdf->SetFont('arial', '', 6);
    imprimirNotaRodape($pdf);
}

function imprimirNotaRodape($pdf)
{   
    $y = $pdf->getY();
    $pdf->setY($pdf->h - 20);
    $pdf->Cell(0, 4, "¹ Atualizar o CPF em uma agência do Banco do Brasil, da CAIXA ou dos CORREIOS.", 0, 1, 'R');
    $pdf->Cell(0, 4, "² Ligar para 135 e agendar o atendimento em uma agência da Previdência Social.", 0, 1, 'R');
    $pdf->setY($y);
}