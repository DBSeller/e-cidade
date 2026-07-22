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

namespace App\Domain\Financeiro\Planejamento\Relatorios;

/**
 * Class ProjecaoDespesaAgrupadaSinteticaPdf
 * @package App\Domain\Financeiro\Planejamento\Relatorios
 */
class ProjecaoDespesaAgrupadaSinteticaPdf extends Pdf
{
    protected $titulo = 'Demonstrativo das Projeções da Despesa - Sintético';

    protected $fonte = 7;

    public function emitir()
    {
        $this->headers($this->titulo);
        $this->capa($this->titulo);
        $this->imprimeDados();

        $filename = sprintf('tmp/projecao-despesa-por-agrupador-%s.pdf', time());
        $this->Output('F', $filename, false);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function imprimeDados()
    {
        $this->cabecalho();
        foreach ($this->dados['dados'] as $dado) {
            if (isset($dado->iniciativas)) {
                $this->imprimeProgramaTitulo($dado);
            } else {
                $this->imprimePrograma($dado);
            }
        }
        $this->imprimeTotalizadores($this->dados['totalizador']);
    }

    private function descricao($formatado, $descricao)
    {
        return sprintf('%s - %s', $formatado, $descricao);
    }

    private function imprimeValores($valores)
    {
        foreach ($this->exercicios as $exercicio) {
            $valor = formataValorMonetario($valores[$exercicio]);
            $this->cellAdapt($this->fonte, $this->wValor, 4, $valor, 1, 0);
        }
    }

    private function imprimePrograma($dado, $bold = false)
    {
        if ($bold) {
            $this->SetFont('Arial', 'B', $this->fonte);
        }

        $descricao = $this->descricao($dado->formatado, $dado->descricao);
        $this->Cell($this->wTitulo, 4, $descricao, 1, 0);

        $this->imprimeValores($dado->valores);
        $this->Ln();

        $this->SetFont('Arial', '', $this->fonte);
    }

    private function imprimeProgramaTitulo($dado)
    {
        $this->imprimePrograma($dado, true);
        $this->imprimeTotalIniciativa($dado->valoresIniciativa);
        foreach ($dado->iniciativas as $iniciativa) {
            $descricao = $this->descricao($iniciativa->formatado, $iniciativa->descricao);
            $this->Cell($this->wTitulo, 4, "   " . $descricao, 1, 0);
            $this->imprimeValores($iniciativa->valores);
            $this->Ln();
        }

        $this->Ln();
    }

    private function imprimeTotalIniciativa($valoresIniciativa)
    {
        $this->SetFont('Arial', 'B', $this->fonte);


        $this->Cell($this->wTitulo, 4, 'Total das Iniciativas', 1, 0);

        $this->imprimeValores($valoresIniciativa);
        $this->Ln();

        $this->SetFont('Arial', '', $this->fonte);
    }

    private function cabecalho()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wTitulo, 5, 'Descrição', 1, 0);
        foreach ($this->exercicios as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 1, 0);
        }
        $this->Ln();
        $this->SetFont('Arial', '', $this->fonte);
    }

    private function imprimeTotalizadores($valores)
    {
        $this->SetFont('Arial', 'B', 7);
        $this->Cell($this->wTitulo, 4, 'Total', 1, 0, 'R');
        $this->imprimeValores($valores);
    }
}
