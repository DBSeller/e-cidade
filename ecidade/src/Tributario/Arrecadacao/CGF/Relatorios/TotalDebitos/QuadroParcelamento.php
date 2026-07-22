<?php

namespace ECidade\Tributario\Arrecadacao\CGF\Relatorios\TotalDebitos;

use PDF;

class QuadroParcelamento
{
    const LARGURA_PARCELAMENTO = 49.5;
    const LARGURA_NUMPRE = 49.5;
    const LARGURA_PROCESSO_FORO = 49.5;
    const LARGURA_INICIAL = 49.5;
    const ALTURA = 4;

    private $juridico = array();
    private $parcelamentos = array();
    private $quantidadeIniciais = array();
    private $pdf;

    public function __construct(PDF $pdf)
    {
        $this->pdf = $pdf;
    }

    public function adicionarDados($dados)
    {
        if (!empty($dados->numero_parcelamento)) {
            $parcelamentos = array();
            $this->quantidadeIniciais[$dados->numpre] = 0;

            foreach ($dados->processo_foro as $processosForo) {
                $parcelamentos[$processosForo->processo_foro][] = $processosForo->inicial;
                $this->quantidadeIniciais[$dados->numpre]++;
            }

            $this->juridico[$dados->numpre] = $parcelamentos;
            $this->parcelamentos[$dados->numpre] = $dados->numero_parcelamento;
        }
    }

    public function montarQuadro()
    {
        if ($this->possuiJuridico()) {
            $this->montarCabecalho();
            $this->montarLinhas();
        }
    }

    private function possuiJuridico()
    {
        return count($this->juridico) > 0;
    }

    private function montarCabecalho()
    {
        $this->pdf->Ln();
        $this->pdf->SetX(5);
        $this->pdf->SetFont('arial', 'B', 6);
        $this->pdf->Cell(static::LARGURA_PARCELAMENTO, static::ALTURA, 'PARCELAMENTO', 1, 0, 'C', 1);
        $this->pdf->Cell(static::LARGURA_NUMPRE, static::ALTURA, 'NUMPRE', 1, 0, 'C', 1);
        $this->pdf->Cell(static::LARGURA_PROCESSO_FORO, static::ALTURA, 'PROCESSO DO FORO', 1, 0, 'C', 1);
        $this->pdf->Cell(static::LARGURA_INICIAL, static::ALTURA, 'INICIAL', 1, 1, 'C', 1);
        $this->pdf->SetFont('arial', '', 6);
    }

    private function montarLinhas()
    {
        $preencher = 0;

        foreach ($this->juridico as $numpre => $processos) {
            $this->pdf->SetX(5);
            $quantidadeIniciais = $this->quantidadeIniciais[$numpre];

            $x = $this->montarCedula(static::LARGURA_PARCELAMENTO, $quantidadeIniciais * static::ALTURA, $this->parcelamentos[$numpre], $preencher);
            $this->montarCedula(static::LARGURA_NUMPRE, $quantidadeIniciais * static::ALTURA, $numpre, $preencher, $x);

            foreach ($processos as $processo => $iniciais) {
                $this->pdf->SetX(104);
                $altura = count($iniciais) > 1 ? count($iniciais) * static::ALTURA : static::ALTURA;
                $this->montarCedula(static::LARGURA_PROCESSO_FORO, $altura, ($processo ?: '-'), $preencher);

                foreach ($iniciais as $inicial) {
                    $this->pdf->SetX(153.5);
                    $this->montarCedula(static::LARGURA_INICIAL, static::ALTURA, $inicial, $preencher);
                    $this->pdf->Ln();
                }
            }

            $preencher = $preencher == 1 ? 0 : 1;
        }
    }

    private function montarCedula($largura, $altura, $valor, $preencher, $x = 5)
    {
        if ($altura > $this->pdf->getAvailHeight()) {
            $this->pdf->AddPage();
            $this->pdf->SetX(5);
            $this->montarCabecalho();
            $this->pdf->SetX(5);
        }

        $y = $this->pdf->GetY();
        $this->pdf->MultiCell($largura, $altura, $valor, 1, 'C', $preencher);
        $x += $largura;
        $this->pdf->SetXY($x, $y);

        return $x;
    }
}
