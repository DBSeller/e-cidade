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
 * Class ProgramaGestao
 * Para impressão dos Programas de Gestão
 * @package App\Domain\Financeiro\Planejamento\Relatorios
 */
class ProgramaGestaoPdf extends PdfDespesa
{
    /**
     * @return array
     */
    public function emitir()
    {
        $this->headers('PROGRAMAS DE GESTÃO');
        $this->capa('PROGRAMAS DE GESTÃO');

        $this->imprimeIdentidadeOrganizacional();

        if (!$this->isPPA) {
            $exercicio = array_shift($this->planejamento['exercicios']);
            $this->planejamento['exercicios'] = [$exercicio];
            $this->wValor = 40;
            $this->wValores = 40;
            $this->wTitulo = $this->wLinha - $this->wValores;
        }

        $this->imprimeProgramas();

        $filename = sprintf('tmp/programae-gestao-%s.pdf', time());
        $this->Output('F', $filename, false);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function imprimeIdentidadeOrganizacional()
    {
        if ($this->dados['filtros']['apresentaIdentidadeOrganizacional']) {
            $this->identidadeOrganizacional();
            $this->comissao();
        }
    }

    private function imprimePlanejamento($planejamento)
    {
        $this->addTitulo(sprintf(
            '%s - %s',
            $planejamento['pl2_titulo'],
            $this->periodo
        ));
    }

    private function imprimeProgramas()
    {
        $wDescricao = $this->wTitulo - 15;

        foreach ($this->dados['programas'] as $programa) {
            $this->AddPage();
            $this->SetFont('Arial', 'B', 8);
            $this->tituloCabecalho('Programas de Gestão e Manutenção ao Estado');
            $this->cabecalhoPrograma();

            $this->cellAdapt(8, 15, 5, $programa['formatado'], 1, 0, 'C');
            $this->Cell($wDescricao, 5, $programa['o54_descr'], 1, 0);

            if (!$this->isPPA) {
                $valor = array_shift($programa['valores']);
                $programa['valores'] = [$valor];
            }
            foreach ($programa['valores'] as $valor) {
                $valorFormatado = formataValorMonetario($valor['pl10_valor']);
                $this->cellAdapt(8, $this->wValor, 5, $valorFormatado, 1, 0, 'R');
            }
            $this->Ln();
            $this->imprimeAreaResultado($programa);
            $this->imprimeOrgaos($programa['orgaos']);
            $this->imprimeObjetivos($programa['objetivos']);
            $this->imprimeIniciativas($programa['iniciativas']);
        }
    }

    protected function cabecalhoPrograma()
    {
        $this->SetFont('Arial', 'B', 8);
        $this->Cell($this->wTitulo, 5, '1. Descrição do Programa', 1, 0, 'L', 1);
        $this->Cell($this->wValores, 5, '1.1 Valor do programa', 1, 1, 'L', 1);

        $wDescricao = $this->wTitulo - 15;
        $this->Cell(15, 5, 'Código', 1, 0, 'L', 1);
        $this->Cell($wDescricao, 5, 'Título', 1, 0, 'L', 1);
        $this->imprimeCabelhoExercicios($this->exercicios);

        $this->SetFont('Arial', '', 8);
    }

    private function imprimeCabelhoExercicios(array $exercicios, $quebraLinha = true)
    {
        if (!$this->isPPA) {
            $valor = array_shift($exercicios);
            $exercicios = [$valor];
        }
        foreach ($exercicios as $exercicio) {
            $this->Cell($this->wValor, 5, $exercicio, 1, 0, 'C', 1);
        }

        if ($quebraLinha) {
            $this->Ln();
        }
    }
}
