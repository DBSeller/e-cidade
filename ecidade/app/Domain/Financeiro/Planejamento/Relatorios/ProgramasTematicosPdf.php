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
 * Class ProgramasTematicosPdf
 * Para impressão dos Programas de Temáticos
 * @package App\Domain\Financeiro\Planejamento\Relatorios
 */
class ProgramasTematicosPdf extends PdfDespesa
{
    protected $nivelIniciativa = '1.3.2';

    public function emitir()
    {
        $this->headers('PROGRAMAS TEMÁTICOS');
        $this->capa('PROGRAMAS TEMÁTICOS');

        $this->imprimeIdentidadeOrganizacional();
        $this->imprimeProgramas();
        $filename = sprintf('tmp/programae-estrategico-%s.pdf', time());
        $this->Output('F', $filename, false);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }
    public function imprimeIdentidadeOrganizacional()
    {
        if ($this->apresentaIdentidadeOrganizacional) {
            $this->identidadeOrganizacional();
            $this->comissao();
        }
    }

    public function setFiltros(array $filtros)
    {
        parent::setFiltros($filtros);
        if (isset($filtros['apresentaValoresMetaObjetivo'])) {
            $this->apresentaValoresMetaObjetivo = $filtros['apresentaValoresMetaObjetivo'] == 1;
        }
        if (isset($filtros['apresentaIdentidadeOrganizacional'])) {
            $this->apresentaIdentidadeOrganizacional = $filtros['apresentaIdentidadeOrganizacional'] == 1;
        }
    }

    protected function cabecalhoPrograma()
    {
        if (($this->GetY() + 16) > $this->getH()) {
            $this->AddPage();
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(193, 5, '1 - PROGRAMA', 1, 1, 'L', 1);
        $this->Cell(15, 5, 'Código', 1, 0, 'C', 1);
        $this->Cell(138, 5, 'Descrição', 1, 0, 'C', 1);
        $this->Cell(40, 5, 'Valores do Programa', 1, 1, 'C', 1);
        $this->SetFont('Arial', '', 7);
    }

    protected function cabecalhoIndicadores()
    {
        if (($this->GetY() + 16) > $this->getH()) {
            $this->AddPage();
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(193, 5, '1.1 - Indicadores Vinculados ao Programa', 1, 1, 'L', 1);
        $y = $this->GetY();
        $this->Cell(122, 10, 'Descrição', 'LRT', 0, 'C', 1);
        $this->SetX(132);
        $this->Cell(31, 10, 'Unidade de Medida', 'LRT', 0, 'C', 1);
        $this->SetXY(163, $y);
        $this->Cell(40, 5, 'Referência', 1, 1, 'C', 1);
        $this->SetX(163);
        $this->Cell(9, 5, 'Ano', 1, 0, 'C', 1);
        $this->Cell(31, 5, 'Valor', 1, 1, 'C', 1);
        $this->SetFont('Arial', '', 7);
    }

    protected function cabecalhoObjetivos()
    {
        if (($this->GetY() + 10) > $this->getH()) {
            $this->AddPage();
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(193, 5, '1.3 - Objetivos do Programa', 1, 1, 'L', 1);
        $this->SetFont('Arial', '', 7);
    }

    protected function cabecalhoMetaObjetivo()
    {
        $altura = $this->apresentaValoresMetaObjetivo ? 25 : 15;
        if ($this->getAvailHeight() < $altura) {
            $this->AddPage();
        }
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(193, 5, '1.3.1 - Metas do Objetivo', 1, 1, 'L', 1);

        if ($this->apresentaValoresMetaObjetivo) {
            $this->Cell(153, 5, 'Descrição', 1, 0, 'L', 1);
            $this->Cell(40, 5, 'Indicadores de Resultado', 1, 1, 'L', 1);
        } else {
            $this->Cell(193, 5, 'Descrição', 1, 1, 'L', 1);
        }
        $this->SetFont('Arial', '', 7);
    }

    protected function imprimeProgramas()
    {
        foreach ($this->dados['programas'] as $programa) {
            $this->AddPage();
            $this->imprimePrograma($programa);
        }
    }

    protected function imprimePrograma($programa)
    {
        $this->cabecalhoPrograma();

        $y = $this->GetY();
        $x = $this->GetX();
        $this->Cell(15, 4, $programa['formatado'], 0, 0, 'L');
        $this->Cell(138, 4, $programa['o54_descr'], 0, 0, 'L');
        $this->imprimevalores($programa['valores']);

        $this->Line($x, $y, $x, $this->GetY());
        $this->Line($x + 15, $y, $x + 15, $this->GetY());
        $this->Line($x + 153, $y, $x + 153, $this->GetY());
        $this->Line($x, $this->GetY(), 193, $this->GetY());

        if ($this->planejamento['pl2_composicao'] === 3 && !empty($programa['objetivoEstrategico'])) {
            $this->imprimeObjetivoEstrategico($programa['objetivoEstrategico']);
        }
        $this->imprimeAreaResultado($programa);
        $this->imprimeIndicadores($programa['indicadores']);
        $this->imprimeOrgaos($programa['orgaos']);
        $this->imprimeObjetivos($programa['objetivos']);
    }

    protected function imprimeObjetivoEstrategico($objetivoEstrategico)
    {
        $this->imprimeAreaResultado($objetivoEstrategico['area_resultado']);

        $this->SetFont('Arial', 'B', 8);
        $this->Cell(193, 5, 'Objetivo Estratégico', 1, 1, 'L', 1);
        $y = $this->GetY();

        $this->SetFont('Arial', '', 7);
        $this->MultiCell(193, 4, "Título: {$objetivoEstrategico['pl5_titulo']}");
        if (!empty($objetivoEstrategico['pl5_contextualizacao'])) {
            $this->ln(1);
            $this->MultiCell(193, 4, "Contextualização: {$objetivoEstrategico['pl5_contextualizacao']}");
        }
        if (!empty($objetivoEstrategico['pl5_fonte'])) {
            $this->ln(1);
            $this->MultiCell(193, 4, "Fonte: {$objetivoEstrategico['pl5_fonte']}");
        }

        $yFinal = $this->GetY();
        $x = $this->GetX();

        $this->Line($x, $y, $x, $yFinal);
        $this->Line(203, $y, 203, $yFinal);
        $this->Line($x, $yFinal, $x, $yFinal);
    }

    protected function imprimeIndicadores(array $indicadores)
    {
        $this->cabecalhoIndicadores();

        foreach ($indicadores as $indicador) {
            $this->Cell(122, 5, $indicador['descricao'], 1, 0, 'L');
            $this->cellAdapt(6, 31, 5, $indicador['unidade'], 1, 0, 'L');
            $this->Cell(9, 5, $indicador['ano'], 1, 0, 'C');
            $this->Cell(31, 5, $indicador['indice'], 1, 1, 'R');
        }
    }

    protected function imprimeOrgaos(array $orgaos)
    {
        $this->cabecalhoOrgao();
        foreach ($orgaos as $orgao) {
            $this->Cell(15, 4, $orgao['formatado'], 1, 0, 'L');
            $this->Cell(178, 4, $orgao['descricao'], 1, 1, 'L');
        }
    }
}
