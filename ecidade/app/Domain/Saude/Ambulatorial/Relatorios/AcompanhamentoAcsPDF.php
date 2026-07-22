<?php

namespace App\Domain\Saude\Ambulatorial\Relatorios;

use FpdfMultiCellBorder;

class AcompanhamentoAcsPDF extends FpdfMultiCellBorder
{
    /**
     * @var array
     */
    private $dados;

    public function __construct(array $dados)
    {
        parent::__construct();
        $this->dados = $dados;

        global $head2;

        $head2 = 'Relátorio de Acompanhamentos ACS';
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

    public function emitir()
    {
        $this->initPdf();
        $this->AddPage();
        foreach ($this->dados as $paciente) {
            $this->montaCabecalhoPaciente($paciente);

            $alturaNecessaria = $this->NbLines(80, reset($paciente->acompanhamentos)->s168_evolucao) * 4;
            if ($this->getAvailHeight() < $alturaNecessaria) {
                $this->AddPage();
            }

            $this->montaCabecalho();

            $this->SetFont('Arial', '', 7);
            $linhaImpressa = 0;
            foreach ($paciente->acompanhamentos as $acompanhamento) {
                $linhaImpressa++;
                $color = !($linhaImpressa % 2);
                
                $timestamp = strtotime($acompanhamento->s168_data_hora);
                $alturaNecessaria = $this->NbLines(80, $acompanhamento->s168_evolucao) * 4;
                if ($this->getAvailHeight() < $alturaNecessaria + 4) {
                    $this->AddPage();
                    $this->SetFont('Arial', 'B', 8);
                    $this->Cell(195, 4, "Paciente: {$paciente->id} - {$paciente->nome}", 0, 1);
                    $this->montaCabecalho();
                }

                $this->SetFillColor(240);
                $this->SetFont('Arial', '', 7);
                $this->Cell(15, $alturaNecessaria + 4, date('d/m/Y', $timestamp), 1, 0, 'C', $color);
                $this->Cell(80, $alturaNecessaria + 4, $acompanhamento->profissional->cgm->z01_nome, 1, 0, '', $color);
                
                $x = $this->GetX();
                $y = $this->GetY();
                $this->SetFont('Arial', 'B', 7);
                $this->Cell(20, 4, "Unidade:", 0, 0, '', $color);
                $this->cellAdapt(7, 80, 4, $acompanhamento->unidade->departamento->descrdepto, 0, 0, '', $color);
                $this->SetXY($x, $y + 4);
                $this->Cell(20, $alturaNecessaria, "Evolução:", 0, 0, '', $color);
                $this->SetXY($x + 20, $y + 4);
                $this->SetFont('Arial', '', 7);
                $this->MultiCell(80, 4, $acompanhamento->s168_evolucao, 0, '', $color);
                $this->SetXY($x, $y);
                $this->Cell(100, $alturaNecessaria + 4, '', 1, 1);
            }

            $this->Cell(1, 4, '', 0, 1);
        }

        return $this->imprimir();
    }

    public function imprimir()
    {
        $fileName = 'tmp/acompanhamento_acs' . time() . '.pdf';
        $this->Output($fileName, false, true);

        return [
            "name" => "Relatório de Acompanhamento ACS",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }

    private function montaCabecalhoPaciente($paciente)
    {
        $this->SetFillColor(210);
        $this->SetFont('Arial', 'B', 8);
        $x = $this->GetX();
        $y = $this->GetY();
        $this->Cell(195, 30, '', 1, 1, '', 1);
        $this->SetXY($x, $y + 1);
        $this->Cell(195, 4, "Paciente: {$paciente->id} - {$paciente->nome}", 0, 1);
        $this->Cell(100, 4, "Sexo: {$paciente->sexo}", 0, 0);
        $this->Cell(95, 4, "Data de Nasc.: {$paciente->data_nascimento}", 0, 1);
        $this->Cell(100, 4, "Munic. Nasc.: {$paciente->municipio_nascimento}", 0, 0);
        $this->Cell(95, 4, "CPF: {$paciente->cpf}", 0, 1);
        $this->Cell(100, 4, "Contatos: {$paciente->telefone} - {$paciente->celular}", 0, 0);
        $this->Cell(95, 4, "Cartão SUS: {$paciente->cns}", 0, 1);
        $this->Cell(100, 4, "Nome do Pai: {$paciente->nome_pai}", 0, 0);
        $this->Cell(95, 4, "Nome da Mãe: {$paciente->nome_mae}", 0, 1);
        $this->Cell(100, 4, "Microárea: {$paciente->microarea}", 0, 0);
        $this->Cell(95, 4, "Família: {$paciente->familia}", 0, 1);
        $this->Cell(100, 4, "Endereço: {$paciente->endereco}", 0, 0);
        $this->Cell(95, 4, "Bairro: {$paciente->bairro}", 0, 1);

        $this->SetXY($x, $y + 35);
    }

    private function montaCabecalho()
    {
        $this->SetFillColor(210);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(15, 5, 'Data', 1, 0, 'C', 1);
        $this->Cell(80, 5, 'Profissional', 1, 0, 'C', 1);
        $this->Cell(100, 5, 'Detalhes', 1, 1, 'C', 1);
    }
}
