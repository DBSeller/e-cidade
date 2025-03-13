<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios\BalancetesMensais;

use App\Domain\Configuracao\Services\AssinaturaService;

use DBDate;
use Exception;
use stdClass;
use ECidade\Pdf\Pdf;

class Anexo1PDF extends Pdf
{

    private $dados;
    /**
     * @var string
     */

    /**
     * @var AssinaturaService
     */
    private $assinatura;


    /**
     * @var string
     */

    private $filtros ;

    private $totalLinhas = 38;


    public function setDados($dados)
    {
        $this->dados = $dados;
    }

    public function getDados()
    {
        return $this->dados;
    }

    public function __construct($orientation = 'L', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation);
    }

    public function setFiltros(stdClass $filtros)
    {
        $this->filtros = $filtros;
        $this->assinatura = new AssinaturaService($filtros->DB_instit);

        //dd($this->assinatura->assinaturaContador());

        $mes = $this->getMes($this->filtros->mes);
        $ano = $this->filtros->ano;

        $this->addTitulo("Balancetes Mensais - Anexo I - Balancete Financeiro");
        $this->addTitulo("Competência: $mes / $ano");

        $this->init(false);
        $this->SetAutoPageBreak(false, 15);
    }

    private function getMes($mes)
    {

        $meses=array(
        "1" => "Janeiro",
        "2" => "Feveireiro",
        "3" => "Março",
        "4" => "Abril",
        "5" => "Maio",
        "6" => "Junho",
        "7" => "Julho",
        "8" => "Agosto",
        "9" => "Setembro",
        "10" => "Outubro",
        "11" => "Novembro",
        "12" => "Dezembro");
        return $meses[$mes];
    }


    private function cabecalhoMovimentoExtraOrcamentario($lImprime)
    {

        if ($this->getAvailHeight() < 4 || $lImprime) {
            $this->SetFont('arial', 'b', 7);

            if (!$lImprime) {
                $this->AddPage("L");
            }

            $this->Line(150, $this->getY(), 150, 200);

            $this->SetFont('arial', 'b', 8);
            $this->cell(50, 4, "Movimento Extra-Orçamentário", "", 1, "C", 0);

            $this->cell(145, 4, "Receitas", "", 0, "C", 0);
            $this->cell(140, 4, "Despesas", "", 1, "C", 0);
            $this->SetFont('arial', '', 7);

            $this->SetFont('arial', 'b', 8);
            $this->cell(40, 4, "Conta", "", 0, "C", 0);
            $this->cell(60, 4, "Descrição", "", 0, "C", 0);
            $this->cell(20, 4, "No Mes", "", 0, "C", 0);
            $this->cell(20, 4, "Até o Mes", "", 0, "C", 0);
            $this->cell(40, 4, "Conta", "", 0, "C", 0);
            $this->cell(50, 4, "Descrição", "", 0, "C", 0);
            $this->cell(20, 4, "No Mes", "", 0, "C", 0);
            $this->cell(20, 4, "Até o Mes", "", 1, "C", 0);
            $this->SetFont('arial', '', 6);
        }
    }

    private function separaRegistrosPorPagina($registros)
    {
        $paginas = [];
        $pagina = 1;
        $contador = 0;
        foreach ($registros as $key => $dados) {
            $contador++;
            $paginas[$pagina][] = $key;

            if ($contador === $this->totalLinhas) {
                $pagina += 1;
                $contador = 0;
            }
            foreach ($dados as $dado) {
                $contador++;
                $paginas[$pagina][] = $dado;

                if ($contador === $this->totalLinhas) {
                    $pagina += 1;
                    $contador = 0;
                }
            }
        }
        return $paginas;
    }

    private function organizaRegistros($receitas, $despesas)
    {

        $paginasReceitas = $this->separaRegistrosPorPagina($receitas);
        $paginasDespesas = $this->separaRegistrosPorPagina($despesas);

        $numeroPaginas = max(count($paginasDespesas), count($paginasReceitas));

        $paginasImprimir = [];

        for ($i = 1; $i <= $numeroPaginas; $i++) {
            $paginasImprimir[$i]['receitas'] = [];
            $paginasImprimir[$i]['despesas'] = [];

            if (array_key_exists($i, $paginasReceitas)) {
                $paginasImprimir[$i]['receitas'] = $paginasReceitas[$i];
            }

            if (array_key_exists($i, $paginasDespesas)) {
                $paginasImprimir[$i]['despesas'] = $paginasDespesas[$i];
            }
        }


        return $paginasImprimir;
    }


    public function imprimeRegistroExtraOrcamentario($dados, $setX = false)
    {
        $this->totalLinhas = 38;
        foreach ($dados as $dado) {
            if ($setX) {
                $this->setX(155);
            }
            if (is_object($dado)) {
                $this->Cell(2, 4, '', '', 0);
                $this->Cell(22, 4, $dado->estrutural, '', 0, 'L');
                $this->cellAdapt(7, 70, 4, $dado->descricao_conta, '', 0);
                $this->Cell(20, 4, $dado->noMes, '', 0, 'R');
                $this->Cell(20, 4, $dado->acumulado, '', 1, 'R');
            } else {
                $this->SetFont('arial', 'b', 7);
                $this->Cell(145, 4, $dado, '', 1);
                $this->SetFont('arial', '', 7);
            }
        }
    }

    public function gerarMovimentoExtraOrcamentario(stdClass $dados)
    {

        $aReceitas = $dados->receitas->dados;
        $aDespesas = $dados->despesas->dados;

        $this->AddPage("L");
        $this->cabecalhoMovimentoExtraOrcamentario(true);

        $altY = $this->GetY();
        $registrosPorPagina = $this->organizaRegistros($aReceitas, $aDespesas);

        foreach ($registrosPorPagina as $pagina => $registros) {
            $this->imprimeRegistroExtraOrcamentario($registros['receitas'], false);

            $nY = $this->GetY();
            $this->setY($altY);
            $this->imprimeRegistroExtraOrcamentario($registros['despesas'], true);

            $this->cabecalhoMovimentoExtraOrcamentario(false);

            if (count($registros['despesas']) > count($registros['receitas'])) {
                $nY = $this->GetY();
            }
        }
        if (isset($nY)) {
            $this->setY($nY);
        }
        $this->SetFont('arial', 'b', 8);

        $this->Cell(85, 4, 'Total: ', "", 0, "R", 0);

        $this->Cell(25, 4, db_formatar($dados->receitas->totalNoMes, "f"), "", 0, "R", 0);
        $this->Cell(25, 4, db_formatar($dados->receitas->totalAcumulado, "f"), "", 0, "R", 0);

        $this->Cell(95, 4, '', "", 0, "R", 0);
        $this->Cell(25, 4, db_formatar($dados->despesas->totalNoMes, "f"), "", 0, "R", 0);
        $this->Cell(25, 4, db_formatar($dados->despesas->totalAcumulado, "f"), "", 1, "R", 0);
        $this->SetFont('arial', '', 7);
    }



    public function cabecalhoMovimentoOrcamentario($lImprime, $lSubtitulo)
    {
        if ($lSubtitulo) {
            $this->SetFont('arial', 'b', 7);
            $this->cell(135, 4, "Movimento Orçamentário", "R", 1, "L", 0);
        }
        if ($this->getAvailHeight() < 4 || $lImprime) {
            $this->SetFont('arial', 'b', 7);

            if (!$lImprime) {
                $this->AddPage("L");
            }
            $this->cell(20, 4, "Elemento", "", 0, "C", 0);
            $this->cell(65, 4, "Receitas", "", 0, "C", 0);
            $this->cell(25, 4, "No Mês", "", 0, "C", 0);
            $this->cell(25, 4, "Até o Mês", "R", 0, "C", 0);

            $this->cell(20, 4, "Elemento", "L", 0, "C", 0);
            $this->cell(65, 4, "Despesas", "", 0, "C", 0);
            $this->cell(25, 4, "No Mês", "", 0, "C", 0);
            $this->cell(25, 4, "Até o Mês", "", 1, "C", 0);


            $this->SetFont('arial', '', 6);
        }
    }



    private function imprimeRegistroOrcamentario($dados, $setX = false)
    {

        $this->totalLinhas = 35;

        foreach ($dados as $dado) {
            if ($setX) {
                $this->setX(155);
            }
            if (is_object($dado)) {
                $this->cell(20, 4, $dado->elemento, "", 0, "L", 0);
                $this->cell(65, 4, $dado->descricao, "", 0, "L", 0);
                $this->cell(25, 4, $dado->periodo, "", 0, "R", 0);
                $this->cell(25, 4, $dado->acumulado, "R", 1, "R", 0);
            } else {
            }

            $this->cabecalhoMovimentoOrcamentario(false, false);
        }
    }

    public function gerarMovimentoOrcamentario(stdClass $dados)
    {


        $aReceitas = $dados->receitas;
        $aDespesas = $dados->despesas;

        $iTotalReceitas = count($aReceitas);
        $iTotalDespesas = count($aDespesas);

        $lQuebraReceita = 0;
        $lQuebraDespesa = 1;

        $iContadorMaximo = $iTotalReceitas;
        if ($iTotalDespesas > $iTotalReceitas) {
            $iContadorMaximo = $iTotalDespesas;
        }

        $this->cabecalhoMovimentoOrcamentario(true, true);
        for ($i = 0; $i <= $iContadorMaximo; $i++) {
            // RECEITAS
            if ($i >= $iTotalDespesas) {
                $lQuebraReceita = 1;
            }

            if ($i < $iTotalReceitas) {
                $oReceita = $aReceitas[$i];
                $elementoReceita = $oReceita->elemento;
                $descricaoReceita = $oReceita->descricao;
                $mesReceita = $oReceita->periodo;
                $acumuladoReceita = $oReceita->acumulado;

                $this->cell(20, 4, $elementoReceita, "", 0, "L", 0);
                $this->cell(65, 4, $descricaoReceita, "", 0, "L", 0);
                $this->cell(25, 4, $mesReceita, "", 0, "R", 0);
                $this->cell(25, 4, $acumuladoReceita, "R", $lQuebraReceita, "R", 0);
            }

            // DESPESAS

            if ($i < $iTotalDespesas) {
                $oDespesa = $aDespesas[$i];
                $elementoDespesa = $oDespesa->elemento;
                $descricaoDespesa = $oDespesa->descricao;
                $mesDespesa = $oDespesa->periodo;
                $acumuladoDespesa = $oDespesa->acumulado;

                $this->cell(20, 4, $elementoDespesa, "L", 0, "L", 0);
                $this->cell(65, 4, $descricaoDespesa, "", 0, "L", 0);
                $this->cell(25, 4, $mesDespesa, "", 0, "R", 0);
                $this->cell(25, 4, $acumuladoDespesa, "", $lQuebraDespesa, "R", 0);
            }

            $this->cabecalhoMovimentoOrcamentario(false, false);
        }

        //TOTALIZADORES
        $this->SetFont('arial', 'B', 7);
        $this->cell(85, 4, "Total do Movimento Orçamentário:", "", 0, "R", 0);
        $this->cell(25, 4, db_formatar($dados->oTotalReceitas->totalPeriodo, "f"), "", 0, "R", 0);
        $this->cell(25, 4, db_formatar($dados->oTotalReceitas->totalAcumulado, "f"), "R", 0, "R", 0);
        $this->cell(85, 4, "", "L", 0, "R", 0);
        $this->cell(25, 4, db_formatar($dados->oTotalDespesas->totalPago, 'f'), "", 0, "R", 0);
        $this->cell(25, 4, db_formatar($dados->oTotalDespesas->totalPagoAcumulado, "f"), "", 1, "R", 0);
    }

    // MOVIMENTO ORÇAMENTÁRIO
    // MOVIMENTO EXTRA ORÇAMENTÁRIO
    // SALDO DISPONÍVEL

    /**
     * @param stdClass $dados
     * @param DBDate $dataInicial
     * @param DBDate $dataFinal
     * @return string
     * @throws Exception
     */
    public function emitir()
    {
        $dados = $this->getDados();

        $this->AddPage("L");
        $this->SetFont('Arial', '', 7);
        $this->gerarMovimentoOrcamentario($dados->dadosMovimentoOrcamentario);
        $this->gerarMovimentoExtraOrcamentario($dados->dadosExtraOrcamentario);
        $this->gerarSaldoDisponivel($dados->saldoDisponivel);
        $this->gerarTotalizadores();

        return $this->imprimir();
    }

    public function cabecalhoSaldoDisponivel($lImprime)
    {

        if ($this->getAvailHeight() < 4 || $lImprime) {
            $this->SetFont('arial', 'b', 7);

            if (!$lImprime) {
                $this->AddPage("L");
            }
            $this->cell(120, 4, "", "", 0, "C", 0);
            $this->cell(80, 4, "Receitas", "R", 0, "C", 0);
            $this->cell(80, 4, "Despesas", "L", 0, "C", 0);
            $this->cell(50, 4, "", "", 1, "C", 0);

            $this->cell(20, 4, "Banco", "", 0, "C", 0);
            $this->cell(100, 4, "Descricao", "", 0, "C", 0);

            $this->cell(40, 4, "No Mês", "", 0, "C", 0);
            $this->cell(40, 4, "Até o Mês", "R", 0, "C", 0);

            $this->cell(40, 4, "No Mês", "L", 0, "C", 0);
            $this->cell(40, 4, "Até o Mês", "", 1, "C", 0);

            $this->SetFont('arial', '', 6);
        }
    }

    public function gerarTotalizadores()
    {
        $dados = $this->getDados();
        $movimentoOrcamentario = $dados->dadosMovimentoOrcamentario;
        $movimentoExtra = $dados->dadosExtraOrcamentario;
        $saldoDisponivel = $dados->saldoDisponivel;

        $totalReceitaMes = $movimentoOrcamentario->oTotalReceitas->totalPeriodo +
          $movimentoExtra->receitas->totalNoMes +
          $saldoDisponivel->saldo_anterior_debitoTotal;

        $totalReceitaAcumulada = $movimentoOrcamentario->oTotalReceitas->totalAcumulado +
          $movimentoExtra->receitas->totalAcumulado +
          $saldoDisponivel->acumulado_debitoTotal;

        $totalDespesaMes = $movimentoOrcamentario->oTotalDespesas->totalPago +
          $movimentoExtra->despesas->totalNoMes +
          $saldoDisponivel->saldo_anterior_creditoTotal;


        $totalDespesaAcumulada = $movimentoOrcamentario->oTotalDespesas->totalPagoAcumulado +
          $movimentoExtra->despesas->totalAcumulado +
          $saldoDisponivel->acumulado_creditoTotal;



        if ($this->getAvailHeight() < 5) {
            $this->AddPage("L");
        }

        $this->SetFont('arial', 'B', 8);
        $this->cell(200, 8, "", "R", 0, "R", 0);
        $this->cell(80, 8, "", "L", 1, "R", 0);

        $this->cell(20, 4, "", "", 0, "R", 0);
        $this->cell(100, 4, "Total Geral: ", "", 0, "R", 0);

        $this->cell(40, 4, db_formatar($totalReceitaMes, "f"), "", 0, "R", 0);
        $this->cell(40, 4, db_formatar($totalReceitaAcumulada, "f"), "R", 0, "R", 0);

        $this->cell(40, 4, db_formatar($totalDespesaMes, "f"), "L", 0, "R", 0);
        $this->cell(40, 4, db_formatar($totalDespesaAcumulada, "f"), "", 1, "R", 0);
    }

    public function gerarSaldoDisponivel(stdClass $dados)
    {

        $this->AddPage("L");
        $this->SetFont('arial', 'b', 7);
        $this->cell(180, 4, "Saldo Disponível", "", 1, "L", 0);

        $this->cabecalhoSaldoDisponivel(true);

        foreach ($dados->saldoDisponivel as $oDados) {
            $this->cell(20, 4, $oDados->descr_conta_bancaria, "", 0, "R", 0);
            $this->cell(100, 4, $oDados->descricao, "", 0, "L", 0);

            $this->cell(40, 4, $oDados->saldo_anterior_debito, "", 0, "R", 0);
            $this->cell(40, 4, $oDados->acumulado_debito, "R", 0, "R", 0);

            $this->cell(40, 4, $oDados->saldo_anterior_credito, "L", 0, "R", 0);
            $this->cell(40, 4, $oDados->acumulado_credito, "", 1, "R", 0);

            $this->cabecalhoSaldoDisponivel(false);
        }

        $this->SetFont('arial', 'b', 7);
        $this->cell(20, 4, "", "", 0, "R", 0);
        $this->cell(100, 4, "Total: ", "", 0, "R", 0);

        $this->cell(40, 4, db_formatar($dados->saldo_anterior_debitoTotal, "f"), "", 0, "R", 0);
        $this->cell(40, 4, db_formatar($dados->acumulado_debitoTotal, "f"), "R", 0, "R", 0);

        $this->cell(40, 4, db_formatar($dados->saldo_anterior_creditoTotal, "f"), "L", 0, "R", 0);
        $this->cell(40, 4, db_formatar($dados->acumulado_creditoTotal, "f"), "", 1, "R", 0);
    }

    protected function imprimir()
    {
        $fileName = 'tmp/balancetes_mensais_anexo1_' . time() . '.pdf';
        $this->Output('F', $fileName, false);
        return  $fileName;
    }
}
