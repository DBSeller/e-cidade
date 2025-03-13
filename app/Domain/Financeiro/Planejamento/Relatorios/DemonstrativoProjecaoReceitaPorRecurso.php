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

use stdClass;

class DemonstrativoProjecaoReceitaPorRecurso extends DemonstrativoProjecaoReceita
{

    protected $titulo = 'Demonstrativo das Projeções da Receita - por Recurso';

    protected function cabecalhoReceita()
    {
        $exercicioProjecao = $this->dados['planejamento']['pl2_ano_inicial'] - 1;
        $exercicios = $this->dados['planejamento']['exercicios'];
        $exerciciosAnteriores = $this->dados['exerciciosAnteriores'];

        $totalColunaValores = count($exercicios) + count($exerciciosAnteriores) + 1;

        $this->wValor = ($this->wLinha - $this->wDadosReceita) / $totalColunaValores;

        $this->wValorArrecadados = $this->wValor * count($exerciciosAnteriores);
        $this->wValoresProjetados = $this->wValor * count($exercicios);

        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wDadosReceita, 5, 'Recursos', 1, 0, 'C');
        $this->Cell($this->wValorArrecadados, 5, 'Valores Arrecadados', 1, 0, 'C');
        $this->cellAdapt(8, $this->wValor, 5, "Previsão Atualizada", 'LRT', 0, 'C');
        $this->Cell($this->wValoresProjetados, 5, "Valores Projetados", 1, 1, 'C');

        $this->Cell(20, 5, 'Recurso', 1, 0, 'C');
        $this->Cell(76, 5, 'Descrição', 1, 0, 'C');

        foreach ($this->dados['exerciciosAnteriores'] as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 1, 0, 'C');
        }

        $this->Cell($this->wValor, 5, $exercicioProjecao, 'RLB', 0, 'C');

        foreach ($exercicios as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 1, 0, 'C');
        }

        $this->Ln();
        $this->SetFont('Arial', '', 7);
    }

    /**
     * @param $recurso
     * @param $exerciciosAnteriores
     * @param $exercicios
     */
    protected function imprimeLinha(stdClass $recurso, $exerciciosAnteriores, $exercicios)
    {
        $this->SetFont('Arial', '', 6);
        $this->cellAdapt(6, 20, 5, "$recurso->recurso - $recurso->complemento", 1, 0, 'C');
        $this->cellAdapt(6, 76, 5, $recurso->descricao, 1, 0, 'L');
        foreach ($exerciciosAnteriores as $exercicio) {
            $this->imprimeValor($recurso->{"arrecadado_{$exercicio}"});
        }
        $this->imprimeValor($recurso->valor_base);
        foreach ($exercicios as $exercicio) {
            $this->imprimeValor($recurso->{"valor_{$exercicio}"});
        }

        $this->ln();
    }
}
