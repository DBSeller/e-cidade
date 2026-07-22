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
 * Class ProjecaoDespesaAgrupadaPdf
 * @package App\Domain\Financeiro\Planejamento\Relatorios
 */
class ProjecaoDespesaAgrupadaPdf extends Pdf
{
    protected $titulo = 'Demonstrativo das Projeções da Despesa';
    protected $wLinha = 279;

    protected $fonte = 7;

    protected $wValoresProjetados = 20;
    /**
     * @var float|int
     */
    private $wTituloTotalizador;

    public function __construct($orientation = 'L', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);
    }

    public function emitir()
    {
        $this->calculaColunas();
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

    /**
     * @param $titulo
     */
    protected function capa($titulo)
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 30);
        $this->SetY(105);
        $this->Cell($this->wLinha, 12, $titulo, 0, 1, 'R');
        $this->Cell($this->wLinha, 12, $this->getTituloPlano(), 0, 1, 'R');
        $this->Cell($this->wLinha, 12, $this->periodo, 0, 1, 'R');
    }

    private function imprimeDados()
    {
        $this->cabecalho();
        foreach ($this->dados['dados'] as $dado) {
            $descricao = sprintf('%s - %s', $dado->formatado, $dado->descricao);
            $this->cellAdapt($this->fonte, $this->wTitulo, 4, $descricao, 1, 0, 'L');

            foreach ($this->dados['exerciciosAnteriores'] as $exercicio) {
                $valor = formataValorMonetario($dado->exercicioAnteriores[$exercicio]);
                $this->cellAdapt($this->fonte, $this->wValor, 4, $valor, 1, 0, 'R');
            }

            $this->cellAdapt($this->fonte, $this->wValor, 4, formataValorMonetario($dado->valorBase), 1, 0, 'R');

            foreach ($this->exercicios as $exercicio) {
                $valor = formataValorMonetario($dado->exerciciosPlanejamento[$exercicio]);
                $this->cellAdapt($this->fonte, $this->wValor, 4, $valor, 1, 0, 'R');
            }
            $this->Ln();
        }

        $totalizador = $this->dados['totalizador'];
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wTituloTotalizador, 4, 'Total', 1, 0, 'R');
        $this->cellAdapt($this->fonte, $this->wValor, 4, formataValorMonetario($totalizador->valorBase), 1, 0, 'R');
        foreach ($this->exercicios as $exercicio) {
            $valor = formataValorMonetario($totalizador->exercicios[$exercicio]);
            $this->cellAdapt($this->fonte, $this->wValor, 4, $valor, 1, 0, 'R');
        }
        $this->Ln();
    }

    private function cabecalho()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wTitulo, 5, 'Dados da Despesa', 1, 0, 'C');
        $this->Cell($this->wValores, 5, 'Valores Liquidados', 1, 0, 'C');
        $this->Cell($this->wValor, 5, 'Valor', 'LRT', 0, 'C');
        $this->cellAdapt($this->fonte, $this->wValoresProjetados, 5, 'Valores Projetados', 1, 1, 'C');

        $this->Cell($this->wTitulo, 5, $this->dados['agrupador'], 1, 0, 'C');
        foreach ($this->dados['exerciciosAnteriores'] as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 'LRB', 0, 'C');
        }
        $this->Cell($this->wValor, 5, 'Base', 'LRB', 0, 'C');

        foreach ($this->exercicios as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 1, 0, 'C');
        }
        $this->Ln();
        $this->SetFont('Arial', '', 7);
    }

    private function calculaColunas()
    {
        $totalExercicioAnteriores = count($this->dados['exerciciosAnteriores']);
        $totalExerciciosImprimir = count($this->exercicios);
        $wExercicios = ($totalExercicioAnteriores + $totalExerciciosImprimir) * $this->wValor;
        $wExercicios += $this->wValor;

        $this->wTitulo = $this->wLinha - $wExercicios;
        $this->wTituloTotalizador = $this->wTitulo + ($this->wValor * $totalExercicioAnteriores);

        $this->wValores = $this->wValor * $totalExercicioAnteriores;
        $this->wValoresProjetados = $this->wValor * $totalExerciciosImprimir;
    }
}
