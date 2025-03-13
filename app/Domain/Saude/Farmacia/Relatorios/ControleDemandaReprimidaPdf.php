<?php

namespace App\Domain\Saude\Farmacia\Relatorios;

use ECidade\Pdf\Pdf;

class ControleDemandaReprimidaPdf extends Pdf
{
    /**
     * @var array
     */
    private $dados;

    public function __construct(array $dados)
    {
        parent::__construct();
        $this->dados = $dados;

        $this->addTitulo('Relatório Controle de Demanda Reprimida');
    }

    /**
     * Adiciona um titulo informativo do periodo dos dados gerados no relatório
     * @param \DateTime $periodoInicial
     * @param \DateTime $periodoFinal
     * @return ControleDemandaReprimidaPdf
     */
    public function setPeriodo(\DateTime $periodoInicial, \DateTime $periodoFinal)
    {
        $this->addTitulo('');
        $this->addTitulo("Período: {$periodoInicial->format('d/m/Y')} até {$periodoFinal->format('d/m/Y')}");

        return $this;
    }

    /**
     * Adiciona um titulo informativo dos departamentos selecionados
     * @param array $departamentos
     * @return ControleDemandaReprimidaPdf
     */
    public function setDepartamentos(array $departamentos)
    {
        $departamentos = implode(', ', $departamentos);
        $this->addTitulo("Departamentos Selecionados: {$departamentos}");
        
        return $this;
    }

    public function emitir($ordem, $somenteTotal = false, $exibeObservacoes = false)
    {
        $this->initPdf();

        if (!$somenteTotal) {
            $this->addPage();
            $this->montaCabecalho($ordem);
        }

        $totalizador = [];
        $linhaImpressa = 0;
        foreach ($this->dados as $dado) {
            if (!$somenteTotal) {
                $this->montaLinha($dado, $ordem, $exibeObservacoes, $linhaImpressa);
            }

            $totalizador = $this->totalizar($dado, $totalizador);
        }
        // garante a ordenação em ordem alfabetica
        usort($totalizador, function ($a, $b) {
            return strcmp($a->descricao, $b->descricao);
        });

        $this->montaTotal($totalizador);

        return $this->imprimir();
    }

    public function imprimir()
    {
        $fileName = 'tmp/controle_demanda_reprimida' . time() . '.pdf';
        $this->output('F', $fileName);

        return [
            "name" => "Relatório Controle de Demanda Reprimida",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }

    private function initPdf()
    {
        $this->mostrarRodape();
        $this->mostrarTotalDePaginas();
        $this->setMargins(8, 8, 8);
        $this->setAutoPageBreak(false, 10);
        $this->aliasNbPages();
        $this->setFillColor(235);
        $this->setFont('Arial', 'B', 9);
        $this->exibeHeader();
    }

    private function montaCabecalho($ordem)
    {
        $this->setFont('ARIAL', 'B', 8);

        switch ($ordem) {
            case 1:
                $this->cell(65, 5, 'MEDICAMENTO', 1, 0, 'C', 1);
                $this->cell(25, 5, 'DATA', 1, 0, 'C', 1);
                $this->cell(65, 5, 'PACIENTE', 1, 0, 'C', 1);
                $this->cell(15, 5, 'QUANT.', 1, 0, 'C', 1);
                $this->cell(25, 5, 'USUÁRIO', 1, 1, 'C', 1);
                break;
            case 2:
                $this->cell(65, 5, 'PACIENTE', 1, 0, 'C', 1);
                $this->cell(25, 5, 'DATA', 1, 0, 'C', 1);
                $this->cell(65, 5, 'MEDICAMENTO', 1, 0, 'C', 1);
                $this->cell(15, 5, 'QUANT.', 1, 0, 'C', 1);
                $this->cell(25, 5, 'USUÁRIO', 1, 1, 'C', 1);
                break;
            default:
                $this->cell(25, 5, 'DATA', 1, 0, 'C', 1);
                $this->cell(65, 5, 'PACIENTE', 1, 0, 'C', 1);
                $this->cell(65, 5, 'MEDICAMENTO', 1, 0, 'C', 1);
                $this->cell(15, 5, 'QUANT.', 1, 0, 'C', 1);
                $this->cell(25, 5, 'USUÁRIO', 1, 1, 'C', 1);
        }
    }

    private function montaLinha($demanda, $ordem, $exibeObservacoes, &$linhaImpressa)
    {
        $alturaNecessaria = 4;
        if ($exibeObservacoes) {
            $alturaObservacoes = $this->nbLines(173, $demanda->observacoes) * 4;
            $alturaNecessaria += $alturaObservacoes;
        }
        if ($this->getAvailableHeight() < $alturaNecessaria) {
            $linhaImpressa = 0;
            $this->addPage();
            $this->montaCabecalho($ordem);
        }
        $linhaImpressa++;
        $cor = !($linhaImpressa % 2);
        $this->setFont('ARIAL', '', 7);

        switch ($ordem) {
            case 1:
                $this->cell(65, 4, "{$demanda->idMedicamento} - {$demanda->descricaoMedicamento}", 1, 0, 'L', $cor);
                $this->cell(25, 4, $demanda->dataHora, 1, 0, 'C', $cor);
                $this->cell(65, 4, "{$demanda->idPaciente} - {$demanda->nomePaciente}", 1, 0, 'L', $cor);
                $this->cell(15, 4, $demanda->quantidade, 1, 0, 'C', $cor);
                $this->cell(25, 4, $demanda->loginUsuario, 1, 1, 'C', $cor);
                break;
            case 2:
                $this->cell(65, 4, "{$demanda->idPaciente} - {$demanda->nomePaciente}", 1, 0, 'L', $cor);
                $this->cell(25, 4, $demanda->dataHora, 1, 0, 'C', $cor);
                $this->cell(65, 4, "{$demanda->idMedicamento} - {$demanda->descricaoMedicamento}", 1, 0, 'L', $cor);
                $this->cell(15, 4, $demanda->quantidade, 1, 0, 'C', $cor);
                $this->cell(25, 4, $demanda->loginUsuario, 1, 1, 'C', $cor);
                break;
            default:
                $this->cell(25, 4, $demanda->dataHora, 1, 0, 'C', $cor);
                $this->cell(65, 4, "{$demanda->idPaciente} - {$demanda->nomePaciente}", 1, 0, 'L', $cor);
                $this->cell(65, 4, "{$demanda->idMedicamento} - {$demanda->descricaoMedicamento}", 1, 0, 'L', $cor);
                $this->cell(15, 4, $demanda->quantidade, 1, 0, 'C', $cor);
                $this->cell(25, 4, $demanda->loginUsuario, 1, 1, 'C', $cor);
        }

        if ($exibeObservacoes) {
            $this->addObservacoes($demanda->observacoes, $alturaObservacoes, $cor);
        }
    }

    private function addObservacoes($observacoes, $altura, $cor)
    {
        $y = $this->getY();
        $x = $this->getX();
        $this->cell(195, $altura, '', 1);
        $this->setXY($x, $y);
        $this->setFont('ARIAL', '', 7);
        $this->cell(22, $altura, "Observações:", 0, 0, 'C', $cor);
        $this->multiCell(173, 4, $observacoes, 0, '', $cor);
        $this->cell(0, 0, '', 0, 1);
    }

    private function totalizar($demanda, &$totalAtual)
    {
        if (!array_key_exists($demanda->idMedicamento, $totalAtual)) {
            $totalAtual[$demanda->idMedicamento] = (object)[
                'id' => $demanda->idMedicamento,
                'descricao' => $demanda->descricaoMedicamento,
                'unidadeMedida' => $demanda->unidadeMedida,
                'quantidade' => 0
            ];
        }

        $totalAtual[$demanda->idMedicamento]->quantidade += $demanda->quantidade;

        return $totalAtual;
    }

    private function montaTotal($dados)
    {
        $this->addPage();
        $this->montaCabecalhoTotal();
        
        $linhaImpressa = 0;
        foreach ($dados as $medicamento) {
            if ($this->getAvailableHeight() < 4) {
                $linhaImpressa = 0;
                $this->addPage();
                $this->montaCabecalhoTotal();
            }
            $linhaImpressa++;
            $cor = !($linhaImpressa % 2);
            $this->setFont('ARIAL', '', 7);

            $this->cell(30, 4, $medicamento->id, 1, 0, 'C', $cor);
            $this->cell(100, 4, $medicamento->descricao, 1, 0, 'L', $cor);
            $this->cell(30, 4, $medicamento->quantidade, 1, 0, 'C', $cor);
            $this->cell(35, 4, $medicamento->unidadeMedida, 1, 1, 'C', $cor);
        }
    }

    private function montaCabecalhoTotal()
    {
        $this->setFont('ARIAL', 'B', 8);

        $this->cell(195, 4, 'TOTAL POR MEDICAMENTO', 0, 1, 'C');
        $this->cell(30, 5, 'CÓDIGO', 1, 0, 'C', 1);
        $this->cell(100, 5, 'MEDICAMENTO', 1, 0, 'C', 1);
        $this->cell(30, 5, 'QUANTIDADE', 1, 0, 'C', 1);
        $this->cell(35, 5, 'UNIDADE', 1, 1, 'C', 1);
    }
}
