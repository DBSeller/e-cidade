<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios;

use stdClass;

class MetasVersusCotasPdf extends Pdf
{

    /**
     * @var bool
     */
    protected $porBimestre;
    /**
     * @var mixed
     */
    protected $tipoAgrupador;


    protected $wPeriodo = 30;


    protected $wValores = 55;
    protected $wValor = 30;
    protected $wPercentual = 25;
    protected $hLinha = 5;

    protected $fonte = 7;

    /**
     * depara para os períodos
     * @var string[]
     */
    protected $descricaoPeriodos = [
        'janeiro' => 'Janeiro',
        'fevereiro' => 'Fevereiro',
        'marco' => 'Março',
        'abril' => 'Abril',
        'maio' => 'Maio',
        'junho' => 'Junho',
        'julho' => 'Julho',
        'agosto' => 'Agosto',
        'setembro' => 'Setembro',
        'outubro' => 'Outubro',
        'novembro' => 'Novembro',
        'dezembro' => 'Dezembro',
        'bimestre_1' => '1º Bimestre',
        'bimestre_2' => '2º Bimestre',
        'bimestre_3' => '3º Bimestre',
        'bimestre_4' => '4º Bimestre',
        'bimestre_5' => '5º Bimestre',
        'bimestre_6' => '6º Bimestre',
    ];

    /**
     * @var mixed
     */
    private $periodosImpressao = [];

    public function setDados(array $dados)
    {
        parent::setDados($dados);

        $this->porBimestre = $this->dados['filtros']['periodicidade'] === 'bimestral';
        $this->tipoAgrupador = $this->dados['filtros']['agruparPor'];
        $this->periodosImpressao = $this->dados['periodosImpressao'];
    }
    public function headers($titulo)
    {
        parent::headers($titulo);

        $recurso = "Agrupado por: Recurso";
        if ($this->tipoAgrupador === 'geral') {
            $recurso = "Agrupado por: Total Geral";
        }
        $this->addTitulo($recurso);

        $periodicidade = "Periodicidade: Mensal";
        if ($this->porBimestre) {
            $periodicidade = "Periodicidade: Bimestral";
        }
        $this->addTitulo($periodicidade);

        if ($this->dados['filtros']['filtrouRecurso']) {
            $this->addTitulo("Filtrou recurso");
        }

        $this->wValores = $this->wValor + $this->wPercentual;
    }

    public function emitir()
    {
        $this->headers('METAS DE ARRECADAÇÃO X COTAS DA DESPESA');
        $this->capa('METAS DE ARRECADAÇÃO X COTAS DA DESPESA');

        $this->imprimeDados();
        $this->imprimeFonteNotas();

        $filename = sprintf('tmp/meta-x-cotas-%s.pdf', time());
        $this->Output('F', $filename, false);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function imprimeDados()
    {
        $this->AddPage();
        if ($this->tipoAgrupador === 'geral') {
            $this->imprimeTotalGeral();
        } else {
            $this->imprimeRecursos();
        }
    }

    protected function imprimeFonteNotas()
    {
        if (!empty($this->dados['fonte'])) {
            $this->imprimeTexto($this->dados['fonte'], $this->fonte);
        }

        if (!empty($this->dados['notaExplicativa'])) {
            $this->imprimeTexto($this->dados['notaExplicativa'], $this->fonte);
        }
    }

    protected function imprimeTexto($texto, $fonteSize)
    {
        $this->SetFont('Arial', '', $fonteSize);
        $linhas = $this->NbLines($this->wLinha, $texto);
        if ($this->getAvailHeight() < ($this->alturaLinha * $linhas)) {
            $this->AddPage();
        }
        $this->MultiCell($this->wLinha, $this->alturaLinha, $texto);
    }

    protected function imprimeLinhaValor($valor, $b = 1, $hLinha = 5, $ln = 0)
    {
        $this->cellAdapt($this->fonte, $this->wValor, $hLinha, formataValorMonetario($valor), $b, $ln, 'R');
    }

    private function imprimeTotalGeral()
    {
        $totalGeral = $this->dados['totalGeral'];
        $this->cabecalho($totalGeral->descricao);

        $this->imprimeTabela($totalGeral);
    }

    private function imprimeRecursos()
    {
        foreach ($this->dados['dados'] as $dado) {
            $this->cabecalho($dado->descricao);
            $this->imprimeTabela($dado);
        }
    }

    /**
     * @param stdClass $objeto
     */
    public function imprimeTabela($objeto)
    {
        foreach ($this->periodosImpressao as $periodo) {
            $descricao = $this->descricaoPeriodos[$periodo];
            $receita = $objeto->{$periodo}->receita;
            $despesa = $objeto->{$periodo}->despesa;

            $this->Cell($this->wPeriodo, $this->hLinha, $descricao, 'TBR', 0, 'L');
            $this->imprimeLinhaValor($receita->valor, 1);
            $this->Cell($this->wPercentual, $this->hLinha, formataValorMonetario($receita->percentual), 1, 0, 'R');
            $this->imprimeLinhaValor($despesa->valor, 1);
            $this->Cell($this->wPercentual, $this->hLinha, formataValorMonetario($despesa->percentual), 1, 0, 'R');
            $diferenca = $objeto->{$periodo}->diferenca;
            $this->Cell($this->wValores, $this->hLinha, formataValorMonetario($diferenca), 'TBL', 1, 'R');
        }

        $this->SetFont('Arial', 'B', $this->fonte);

        $this->Cell($this->wPeriodo, $this->hLinha, 'TOTAL', 'TBR', 0, 'L');
        $this->imprimeLinhaValor($objeto->total_receita, 1);
        $this->Cell($this->wPercentual, $this->hLinha, "100", 1, 0, 'R');
        $this->imprimeLinhaValor($objeto->total_despesa, 1);
        $this->Cell($this->wPercentual, $this->hLinha, "100", 1, 0, 'R');
        $this->Cell($this->wValores, $this->hLinha, formataValorMonetario($objeto->diferenca), 'TBL', 1, 'R');

        $this->SetFont('Arial', '', $this->fonte);
        $this->ln();
    }

    /**
     * @param $descricao
     */
    private function cabecalho($descricao)
    {
        $this->SetFont('Arial', 'B', $this->fonte);
        $h = $this->hLinha * 2;
        $this->cellAdapt($this->fonte, $this->wLinha, $this->hLinha, $descricao, 0, 1);
        $this->Cell($this->wPeriodo, $h, "Período", "TBR", 0, 'C');
        $this->SetX($this->wPeriodo + 10);
        $w = $this->wValor + $this->wPercentual;
        $this->Cell($w, $this->hLinha, "Receita", 1, 0, 'C');
        $this->Cell($w, $this->hLinha, "Despesa", 1, 0, 'C');
        $this->Cell($w, $this->hLinha, "Diferença", 'TBL', 1, 'C');

        $this->SetX($this->wPeriodo + 10);
        $this->Cell($this->wValor, $this->hLinha, "Valor R$", 1, 0, 'C');
        $this->Cell($this->wPercentual, $this->hLinha, "%", 1, 0, 'C');
        $this->Cell($this->wValor, $this->hLinha, "Valor R$", 1, 0, 'C');
        $this->Cell($this->wPercentual, $this->hLinha, "%", 1, 0, 'C');
        $this->Cell($this->wValores, $this->hLinha, "Valor R$", 'TBL', 1, 'C');

        $this->SetFont('Arial', '', $this->fonte);
    }
}
