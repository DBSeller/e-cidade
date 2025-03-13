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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019\Layout;


use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018\Layout\AnexoVI as Layout2018;

/**
 * Class AnexoVI
 */
class AnexoVI extends Layout2018
{
    protected function imprimirAjusteMetodologico()
    {
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 6, "AJUSTE METODOLÓGICO", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 6, "Até o {$this->anexo->getPeriodo()->getSigla()} / " . $this->anexo->getAno(), 1, 1, \PDFDocument::ALIGN_CENTER, 1);

        for ($linha = 70; $linha <= 76; $linha++) {

            $dadosLinha = $this->linhas[$linha];
            $borda = $linha === 76 ? '1' : 'LR';
            $preenche = $linha === 76 ? '1' : '0';
            $bold = $linha === 76 ? true : false;

            $this->pdf->setBold($bold);
            $this->pdf->Cell(145, 4, $dadosLinha->descricao, $borda, 0, \PDFDocument::ALIGN_LEFT, $preenche);
            $this->pdf->Cell($this->pdf->getAvailWidth(), 4, trim(db_formatar($dadosLinha->saldo, 'f')), $borda, 1, \PDFDocument::ALIGN_RIGHT, $preenche);
            $this->pdf->setBold(false);
        }

        $this->pdf->ln(4);
        $dadosLinha = $this->linhas[77];
        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 4, $dadosLinha->descricao, '1', 0, \PDFDocument::ALIGN_LEFT, 1);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 4, trim(db_formatar($dadosLinha->saldo, 'f')), '1', 1, \PDFDocument::ALIGN_RIGHT, 1);
        $this->pdf->setBold(false);
        $this->pdf->ln(4);
    }

    protected function imprimirInformacoesAdicionais()
    {
        $this->pdf->cell($this->pdf->getAvailWidth(), 4, "Continua " . $this->pdf->getCurrentPage() . "/{nb}", 0, 1, \PDFDocument::ALIGN_RIGHT);
        $this->pdf->addPage();

        $this->pdf->setBold(true);
        $this->pdf->Cell(145, 6, "INFORMAÇÕES ADICIONAIS", 1, 0, \PDFDocument::ALIGN_CENTER, 1);
        $this->pdf->setBold(false);
        $this->pdf->Cell($this->pdf->getAvailWidth(), 6, "PREVISÃO ORÇAMENTÁRIA", 1, 1, \PDFDocument::ALIGN_CENTER, 1);

        for ($linha = 78; $linha <= 81; $linha++) {

            $dadosLinha = $this->linhas[$linha];
            $identacao = \relatorioContabil::getIdentacao($dadosLinha->nivel);

            $this->pdf->Cell(145, 4, $identacao . $dadosLinha->descricao, 1, 0, \PDFDocument::ALIGN_LEFT, 0);
            $this->pdf->Cell($this->pdf->getAvailWidth(), 4, trim(db_formatar($dadosLinha->previsao, 'f')), 1, 1, \PDFDocument::ALIGN_RIGHT, 0);

        }
    }
}
