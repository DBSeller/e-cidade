<?php

namespace App\Domain\Saude\TFD\Relatorios;

use FpdfMultiCellBorder;
use App\Domain\Saude\TFD\Contracts\ViagensPorMotorista;

/**
 * Classe responsável por montar um relatório, em PDF, com os dados agrupados por motorista
 * @package App\Domain\Saude\TFD\Relatorios
 */
class ViagensPorMotoristaPDF extends FpdfMultiCellBorder implements ViagensPorMotorista
{
    /**
     * @var array $dados
     */
    private $dados;

    private $totalDestino;

    public function __construct(array $dados)
    {
        parent::__construct();
        $this->dados = $dados;
        $this->totalDestino = [
            'geral' => [],
            'motorista' => []
        ];

        global $head2;

        $head2 = 'Relátorio de Viagens por Motorista';
    }

    public function emitir($ordem)
    {
        $this->initPdf();
        $this->AddPage();

        $totalGeralPassageiros = 0;
        $totalGeralViagens = 0;
        $totalGeralKm = 0;

        foreach ($this->dados as $motorista) {
            $this->SetFont('Arial', 'B', 8);

            if ($this->getAvailHeight() < 14) {
                $this->AddPage();
            }
            $this->Cell(120, 4, "Motorista: {$motorista->id} - {$motorista->nome}", 0, 1);
            $this->montaCabecalho($ordem);

            $totalPassageiros = 0;
            $totalKm = 0;

            $linhaImpressa = 0;
            $this->SetFont('Arial', '', 7);
            foreach ($motorista->viagens as $viagem) {
                $linhaImpressa++;
                $color = !($linhaImpressa % 2);

                if ($this->getAvailHeight() < 4) {
                    $this->AddPage();
                    $this->montaCabecalho($ordem);
                    $this->SetFont('Arial', '', 7);
                }
                $this->montaLinha($viagem, $color, $ordem);

                $totalPassageiros += $viagem->passageiros;
                $totalKm += $viagem->km;
                $this->totalizarDestino(1, $viagem->destino);
            }

            $totalViagens = count($motorista->viagens);
            $totalGeralViagens += $totalViagens;
            $totalGeralPassageiros += $totalPassageiros;
            $totalGeralKm += $totalKm;

            if ($this->getAvailHeight() < (count($this->totalDestino['motorista']) * 4) + 12) {
                $this->AddPage();
            }

            $this->Cell(80, 4, "Total de Viagens: {$totalViagens}", 0, 0);
            $this->Cell(80, 4, "Total de Passageiros: {$totalPassageiros}", 0, 0);
            $this->Cell(25, 4, "Total de KM: {$totalKm}", 0, 1);
            $this->Cell(25, 4, 'Total por Destino: ', 0, 0);
            $x = $this->GetX();
            foreach ($this->totalDestino['motorista'] as $destino => $quantidade) {
                $this->Cell(40, 4, "{$destino}: {$quantidade}", 0, 1);
                $this->SetX($x);
            }
            $this->Cell(1, 4, '', 0, 1);

            $this->totalDestino['motorista'] = [];
        }

        if ($this->getAvailHeight() < (count($this->totalDestino['motorista']) * 4) + 12) {
            $this->AddPage();
        }
        
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(195, 4, "TOTAL GERAL", 0, 1);
        $this->Cell(80, 4, "Total de Viagens: {$totalGeralViagens}", 0, 0);
        $this->Cell(80, 4, "Total de Passageiros: {$totalGeralPassageiros}", 0, 0);
        $this->Cell(25, 4, "Total de KM: {$totalGeralKm}", 0, 1);
        $this->Cell(25, 4, 'Total por Destino: ', 0, 0);
        $x = $this->GetX();
        foreach ($this->totalDestino['geral'] as $destino => $quantidade) {
            $this->Cell(40, 4, "{$destino}: {$quantidade}", 0, 1);
            $this->SetX($x);
        }
         
        return $this->imprimir();
    }

    private function totalizarDestino($valor, $index)
    {
        if (!array_key_exists($index, $this->totalDestino['motorista'])) {
            $this->totalDestino['motorista'][$index] = 0;
        }
        $this->totalDestino['motorista'][$index] += $valor;

        if (!array_key_exists($index, $this->totalDestino['geral'])) {
            $this->totalDestino['geral'][$index] = 0;
        }
        $this->totalDestino['geral'][$index] += $valor;
    }

    private function initPdf()
    {
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);
        $this->SetMargins(8, 8, 8);
        $this->Open();
        $this->SetAutoPageBreak(false, 10);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->SetFont('Arial', 'B', 9);
        $this->exibeHeader(true);
    }

    private function montaCabecalho($ordem)
    {
        $this->SetFillColor(210);
        $this->SetFont('Arial', 'B', 8);

        switch ($ordem) {
            case self::ORDEM_DATA:
                $this->Cell(30, 5, 'DATA', 1, 0, 'C', 1);
                $this->Cell(60, 5, 'DESTINO', 1, 0, 'C', 1);
                $this->Cell(40, 5, 'VEICULO', 1, 0, 'C', 1);
                $this->Cell(15, 5, 'PLACA', 1, 0, 'C', 1);
                $this->Cell(30, 5, 'PASSAGEIROS', 1, 0, 'C', 1);
                $this->Cell(20, 5, 'KM', 1, 1, 'C', 1);
                break;
            case self::ORDEM_VEICULO:
                $this->Cell(40, 5, 'VEICULO', 1, 0, 'C', 1);
                $this->Cell(60, 5, 'DESTINO', 1, 0, 'C', 1);
                $this->Cell(30, 5, 'DATA', 1, 0, 'C', 1);
                $this->Cell(15, 5, 'PLACA', 1, 0, 'C', 1);
                $this->Cell(30, 5, 'PASSAGEIROS', 1, 0, 'C', 1);
                $this->Cell(20, 5, 'KM', 1, 1, 'C', 1);
                break;
            default:
                $this->Cell(60, 5, 'DESTINO', 1, 0, 'C', 1);
                $this->Cell(30, 5, 'DATA', 1, 0, 'C', 1);
                $this->Cell(40, 5, 'VEICULO', 1, 0, 'C', 1);
                $this->Cell(15, 5, 'PLACA', 1, 0, 'C', 1);
                $this->Cell(30, 5, 'PASSAGEIROS', 1, 0, 'C', 1);
                $this->Cell(20, 5, 'KM', 1, 1, 'C', 1);
                break;
        }
    }

    private function montaLinha($viagem, $cor, $ordem)
    {
        switch ($ordem) {
            case self::ORDEM_DATA:
                $this->Cell(30, 4, $viagem->data, 0, 0, 'C', $cor);
                $this->Cell(60, 4, $viagem->destino, 0, 0, 'C', $cor);
                $this->Cell(40, 4, $viagem->veiculo, 0, 0, 'C', $cor);
                $this->Cell(15, 4, $viagem->placa, 0, 0, 'C', $cor);
                $this->Cell(30, 4, $viagem->passageiros, 0, 0, 'C', $cor);
                $this->Cell(20, 4, $viagem->km, 0, 1, 'C', $cor);
                break;
            case self::ORDEM_VEICULO:
                $this->Cell(40, 4, $viagem->veiculo, 0, 0, 'C', $cor);
                $this->Cell(60, 4, $viagem->destino, 0, 0, 'C', $cor);
                $this->Cell(30, 4, $viagem->data, 0, 0, 'C', $cor);
                $this->Cell(15, 4, $viagem->placa, 0, 0, 'C', $cor);
                $this->Cell(30, 4, $viagem->passageiros, 0, 0, 'C', $cor);
                $this->Cell(20, 4, $viagem->km, 0, 1, 'C', $cor);
                break;
            default:
                $this->Cell(60, 4, $viagem->destino, 0, 0, 'C', $cor);
                $this->Cell(30, 4, $viagem->data, 0, 0, 'C', $cor);
                $this->Cell(40, 4, $viagem->veiculo, 0, 0, 'C', $cor);
                $this->Cell(15, 4, $viagem->placa, 0, 0, 'C', $cor);
                $this->Cell(30, 4, $viagem->passageiros, 0, 0, 'C', $cor);
                $this->Cell(20, 4, $viagem->km, 0, 1, 'C', $cor);
                break;
        }
    }

    private function imprimir()
    {
        $fileName = 'tmp/viagens_por_motorista' . time() . '.pdf';
        $this->Output($fileName, false, true);

        return [
            "name" => "Relatório de Viagens por Motorista",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }
}
