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

class ProgramasPorElementoPdf extends Pdf
{
    private $wEstrutural = 30;
    private $wDescricao;
    private $wDescricaoElemento;
    private $wRecurso = 20;

    private $hMinimoParaManterMesmaPagina = 28;

    private $apresentarRecurso = false;
    private $apresentarRecursoOriginal = false;

    private $fonte = 6;
    /**
     * @var float|int
     */
    private $wTotalizadores;

    public function setDados(array $dados)
    {
        parent::setDados($dados);
        $this->wTotalizadores = $this->wTitulo;
        $this->wDescricao = $this->wTitulo - $this->wEstrutural;
        $this->wDescricaoElemento = $this->wDescricao;

        $this->apresentarRecurso = $dados['apresentarRecurso'];
        $this->apresentarRecursoOriginal = $dados['apresentarRecursoOriginal'];

        if ($this->apresentarRecursoOriginal) {
            $this->wRecurso = 15;
        }

        if ($this->apresentarRecurso) {
            $this->wDescricaoElemento -= $this->wRecurso;
            if ($this->apresentarRecursoOriginal) {
                $this->wDescricaoElemento -= $this->wRecurso;
            }
            $this->wTitulo = $this->wDescricaoElemento + $this->wEstrutural;
        }

        $this->SetAutoPageBreak(true, 10);
    }

    public function emitir()
    {
        $this->headers('Projeções da Despesa por Elemento');
        $this->capa('Projeções da Despesa por Elemento');
        $this->imprimer();

        $filename = sprintf('tmp/programa-por-elemento-%s.pdf', time());
        $this->Output('F', $filename, false);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function imprimer()
    {
        unset($this->dados['planejamento']);
        $this->cabecalho();
        foreach ($this->dados['dados'] as $dadosOrgao) {
            foreach ($dadosOrgao->dados as $dado) {
                if ($this->getAvailHeight() < $this->hMinimoParaManterMesmaPagina) {
                    $this->AddPage();
                }

                $this->Line(10, $this->GetY(), 203, $this->getY());

                $this->linhaDescricao($dado->orgao);
                $this->linhaDescricao($dado->unidade);
                $this->linhaDescricao($dado->funcao);
                $this->linhaDescricao($dado->subfuncao);
                $this->linhaDescricao($dado->programa);
                $this->linhaTotalizadora($dado->iniciativa, $dado->totalizador);
                foreach ($dado->elementos as $elemento) {
                    $this->linhaElemento($elemento);
                }

                $this->Line(10, $this->GetY(), 203, $this->getY());
            }

            $descricao = sprintf('TOTALIZADOR %s', $dadosOrgao->descricao);
            $this->linhaTotais($descricao, $dadosOrgao->totalizador);
        }

        $this->linhaTotais('TOTALIZADOR GERAL', $this->dados['totalizador']);
    }

    private function cabecalho()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 8);

        $this->Cell($this->wTitulo, 5, 'Estrutural', 0, 0, 'C', 1);

        if ($this->apresentarRecursoOriginal) {
            $this->Cell($this->wRecurso * 2, 5, 'Recursos', 0, 0, 'C', 1);
        }

        if ($this->apresentarRecurso && !$this->apresentarRecursoOriginal) {
            $this->Cell($this->wRecurso, 5, 'Recurso', 0, 0, 'C', 1);
        }

        foreach ($this->exercicios as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 0, 0, 'C', 1);
        }
        $this->Ln();
        $this->SetFont('Arial', '', $this->fonte);
    }

    private function linhaDescricao($dado, $quebraLinha = true, $alignCodigo = 'L')
    {
        $this->Cell($this->wEstrutural, 4, $dado->codigo, 0, 0, $alignCodigo);
        $this->Cell($this->wDescricao, 4, $dado->descricao, 0, 0);
        if ($quebraLinha) {
            $this->Ln();
        }
    }

    private function linhaTotalizadora($iniciativa, $totalizador)
    {
        $this->SetFont('Arial', 'B', $this->fonte);
        $this->linhaDescricao($iniciativa, false);
        foreach ($totalizador as $valor) {
            $this->cellAdapt($this->fonte, $this->wValor, 4, formataValorMonetario($valor), 0, 0, 'R');
        }
        $this->Ln();

        $this->SetFont('Arial', '', $this->fonte);
    }

    private function linhaElemento($elemento)
    {
        $this->SetFont('Arial', '', $this->fonte);
        $this->Cell($this->wEstrutural, 4, $elemento->codigo, 0, 0, 'R');

        $this->cellAdapt($this->fonte, $this->wDescricaoElemento, 4, $elemento->descricao, 0, 0);
        if ($this->apresentarRecursoOriginal) {
            $this->Cell($this->wRecurso, 4, $elemento->recurso_original, 0, 0, 'C');
        }
        if ($this->apresentarRecurso) {
            $this->Cell($this->wRecurso, 4, $elemento->recurso, 0, 0, 'C');
        }

        foreach ($this->exercicios as $exercicio) {
            $valor = collect($elemento->valores)->filter(function ($valor) use ($exercicio) {
                return $valor->ano == $exercicio;
            })->shift();

            $valorApresentar = 0;
            if (!empty($valor)) {
                $valorApresentar = $valor->valor;
            }
            $this->cellAdapt($this->fonte, $this->wValor, 4, formataValorMonetario($valorApresentar), 0, 0, 'R');
        }

        $this->Ln();
    }

    private function linhaTotais($descricao, $valores)
    {
        $this->SetFont('Arial', 'B', $this->fonte);
        $this->Cell($this->wTotalizadores, 5, $descricao, 'TBR', 0);
        foreach ($valores as $valor) {
            $this->cellAdapt($this->fonte, $this->wValor, 5, formataValorMonetario($valor), 'LTB', 0, 'R');
        }
        $this->Ln();
        $this->SetFont('Arial', '', $this->fonte);
    }
}
