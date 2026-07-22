<?php


namespace App\Domain\Financeiro\Contabilidade\Relatorios\Balancete\Receita;

use App\Domain\Financeiro\Contabilidade\Relatorios\Pdf;

/**
 * Class BalanceteReceitaRetrato
 * @package App\Domain\Financeiro\Contabilidade\Relatorios\Balancete\Receita
 */
class BalanceteReceitaPaisagem extends Pdf
{
    /**
     * Array com todas contas do balancete
     * @var array
     */
    private $dadosBalancete = [];


    /**
     * Controle do tamanho das colunas
     */
    private $wReceita = 35;
    private $wDescricao = 85;
    private $wCP = 8;
    private $wReduz = 12;
    private $wRecurso = 12;
    private $wComplemento = 10;
    private $wValores = 21;
    private $wPercentual = 10;
    private $wTotal = 162;

    protected $fonte = 7;

    private $totalValorInicial = 0;
    private $totalPrevisaoAtualizada = 0;
    private $totalPrevisaoAdicional = 0;
    private $totalArrecadadoPeriodo = 0;
    private $totalArrecadadoAcumulado = 0;
    private $totalDiferenca = 0;

    public function __construct($orientation = 'L', $unit = 'mm', $format = 'A4')
    {
        parent::__construct($orientation, $unit, $format);
    }

    public function headers($periodo, $instituicoes)
    {
        $this->addTitulo('BALANCETE DA RECEITA POR COMPLEMENTO');
        $this->addTitulo('');
        $this->addTitulo("PERÍODO: {$periodo}");
        $this->addTitulo("INSTITUIÇÕES: {$instituicoes}");
    }

    public function setDadosBalancete($dadosBalancete)
    {
        $this->dadosBalancete = $dadosBalancete;
    }

    protected function imprimeCabecalho()
    {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 7);
        $this->Cell($this->wReceita, 4, 'RECEITA', 1, 0, 'C');
        $this->Cell($this->wDescricao, 4, 'DESCRIÇÃO', 1, 0, 'C');
        $this->Cell($this->wCP, 4, 'CP', 1, 0, 'C');
        $this->Cell($this->wReduz, 4, 'REDUZ', 1, 0, 'C');
        $this->Cell($this->wRecurso, 4, 'REC.', 1, 0, 'C');
        $this->Cell($this->wComplemento, 4, 'COMPL.', 1, 0, 'C');
        $this->Cell($this->wValores, 4, 'PREVISTO', 1, 0, 'C');
        $this->Cell($this->wValores, 4, 'PREV.ADIC.', 1, 0, 'C');
        $this->Cell($this->wValores, 4, 'ARRECADADO', 1, 0, 'C');
        $this->Cell($this->wValores, 4, 'ARREC. ANO', 1, 0, 'C');
        $this->Cell($this->wValores, 4, 'DIFERENÇA', 1, 0, 'C');
        $this->Cell($this->wPercentual, 4, 'PERC.', 1, 1, 'C');
        $this->SetFont('Arial', '', 7);
    }

    public function imprimir()
    {
        $this->imprimeCabecalho();
        foreach ($this->dadosBalancete as $dado) {
            $this->imprimirValores($dado);
            $this->ln();
        }
        $this->imprimirTotalizador();

        $filename = sprintf('tmp/balancete-receita-%s.pdf', time());
        $this->Output('F', $filename);
        return [
            'pdf' => $filename,
            'pdfLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

    private function imprimirValores($dado)
    {
        $this->quebraPagina();

        $diferenca = $dado->previsao_atualizada - $dado->arrecadado_acumulado;
        $percentual = $this->calculaPercentual(
            $dado->valor_inicial,
            $dado->previsao_atualizada,
            $dado->arrecadado_acumulado
        );

        $fill = $dado->sintetico ? 0 : 1;
        $this->linha($this->wReceita, $dado->mascara, 'L', $fill);
        $this->linha($this->wDescricao, $dado->descricao, 'L', $fill);
        $this->linha($this->wCP, $dado->cp, 'C', $fill);
        $this->linha($this->wReduz, $dado->reduzido, 'C', $fill);
        $this->linha($this->wRecurso, $dado->fonte_recurso, 'C', $fill);
        $this->linha($this->wComplemento, $dado->complemento_lancamento, 'C', $fill);
        $this->linha($this->wValores, formataValorMonetario($dado->valor_inicial), 'R', $fill);
        $this->linha($this->wValores, formataValorMonetario($dado->previsao_adicional), 'R', $fill);
        $this->linha($this->wValores, formataValorMonetario($dado->arrecadado_periodo), 'R', $fill);
        $this->linha($this->wValores, formataValorMonetario($dado->arrecadado_acumulado), 'R', $fill);
        $this->linha($this->wValores, formataValorMonetario($diferenca), 'R', $fill);
        $this->linha($this->wPercentual, db_formatar($percentual, 'f'), 'R', $fill);

        if (!$dado->sintetico) {
            $this->totalValorInicial += $dado->valor_inicial;
            $this->totalPrevisaoAtualizada += $dado->previsao_atualizada;
            $this->totalPrevisaoAdicional += $dado->previsao_adicional;
            $this->totalArrecadadoPeriodo += $dado->arrecadado_periodo;
            $this->totalArrecadadoAcumulado += $dado->arrecadado_acumulado;
            $this->totalDiferenca += $diferenca;
        }
    }

    private function linha($w, $valor, $align = 'L', $fill = 0)
    {
        $this->cellAdapt($this->fonte, $w, 4, $valor, 0, 0, $align, $fill);
    }

    public function calculaPercentual($valorInicial, $previsaoAtualizada, $arrecadadoAcumulado)
    {
        $percentual = 0;
        $x = ($valorInicial + $previsaoAtualizada);
        if ($x > 0) {
            $percentual = ($arrecadadoAcumulado / ($x)) * 100;
        }

        return $percentual;
    }

    private function imprimirTotalizador()
    {
        $this->SetFont('Arial', 'B', 7);
        $this->linha($this->wTotal, 'Total', 'R');

        $this->linha($this->wValores, formataValorMonetario($this->totalValorInicial), 'R');
        $this->linha($this->wValores, formataValorMonetario($this->totalPrevisaoAdicional), 'R');
        $this->linha($this->wValores, formataValorMonetario($this->totalArrecadadoPeriodo), 'R');
        $this->linha($this->wValores, formataValorMonetario($this->totalArrecadadoAcumulado), 'R');
        $this->linha($this->wValores, formataValorMonetario($this->totalDiferenca), 'R');

        $percentual = $this->calculaPercentual(
            $this->totalValorInicial,
            $this->totalPrevisaoAtualizada,
            $this->totalArrecadadoAcumulado
        ) ;

        $this->linha($this->wPercentual, db_formatar($percentual, 'f'), 'R');
    }
}
