<?php

namespace App\Domain\Saude\ESF\Relatorios;

use App\Domain\Saude\ESF\Contracts\IndicadorDesempenhoPdf;
use ECidade\Pdf\Pdf;

/**
 * @package App\Domain\Saude\ESF\Relatorios
 */
class IndicadorUmPdf extends Pdf implements IndicadorDesempenhoPdf
{
    /**
     * @var array
     */
    private $dados;

    public function __construct(array $dados)
    {
        parent::__construct();

        $this->dados = $dados;
        $this->addTitulo('Relatório de Desempenho: Indicador 01');
        $titulo = sprintf(
            '%s, %s.',
            'Proporção de gestantes com pelo menos 6 consultas pré-natal (PN) realizadas',
            'sendo a primeira até a 12ª semana de gestação'
        );
        $this->addTitulo($titulo);
    }

    public function emitir()
    {
        $this->initPdf();
        $this->addPage();

        foreach ($this->dados as $paciente) {
            $this->addCabecalhoPaciente($paciente);
            $this->addInfoAtendimentos($paciente->atendimentos);
        }

        return $this->imprimir();
    }

    public function imprimir()
    {
        $nomeArquivo = 'tmp/indicador_um' . time() . '.pdf';
        $this->output('F', $nomeArquivo);

        return [
            "name" => "Relatório Indicador Desempenho 01",
            "path" => $nomeArquivo,
            'pathExterno' => ECIDADE_REQUEST_PATH . $nomeArquivo
        ];
    }

    private function initPdf()
    {
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);
        $this->SetMargins(8, 8, 8);
        $this->SetAutoPageBreak(false, 10);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->SetFont('Arial', 'B', 9);
        $this->exibeHeader(true);
    }

    private function addCabecalhoPaciente($paciente)
    {
        $alturaNecessaria = 34 + $this->alturaNecessariaCid($paciente->atendimentos);
        if ($this->getAvailableHeight() < $alturaNecessaria) {
            $this->addPage();
        }

        $this->setFont('ARIAL', 'B', 8);
        $x = $this->getX();
        $y = $this->getY();
        $this->cell(195, 20, '', 1, 0, '', true);
        $this->setXY($x, $y);
        $this->cell(18, 5, 'PACIENTE:', 0, 0, 'L', true);
        $this->cell(106, 5, "{$paciente->id} - {$paciente->nome}", 0, 0, 'L', true);
        $this->cell(9, 5, 'CNS:', 0, 0, 'R', true);
        $this->cell(62, 5, $paciente->cns, 0, 1, 'L', true);

        $this->cell(18, 5, 'CPF:', 0, 0, 'L', true);
        $this->cell(80, 5, $paciente->cpf, 0, 0, 'L', true);
        $this->cell(35, 5, 'DATA DE NASCIMENTO:', 0, 0, 'R', true);
        $this->cell(62, 5, $paciente->nascimento, 0, 1, 'L', true);

        $this->cell(18, 5, 'SEXO:', 0, 0, 'L', true);
        $this->cell(80, 5, $paciente->sexo, 0, 0, 'L', true);
        $this->cell(35, 5, 'CONDIÇÃO DE SAÚDE:', 0, 0, 'R', true);
        $this->cell(62, 5, 'PRÉ-NATAL', 0, 1, 'L', true);

        $this->cell(18, 5, 'DUM:', 0, 0, 'L', true);
        $this->cell(97, 5, $paciente->dum, 0, 0, 'L', true);
        $this->cell(18, 5, 'SITUAÇÃO:', 0, 0, 'R', true);
        if (!empty($paciente->dataParto)) {
            $this->cell(19, 5, $paciente->situacao, 0, 0, 'L', true);
            $this->cell(26, 5, '/ DATA DO PARTO:', 0, 0, 'R', true);
            $this->cell(16, 5, $paciente->dataParto, 0, 1, 'L', true);
        } else {
            $this->cell(62, 5, $paciente->situacao, 0, 1, 'L', true);
        }

        $this->setXY($x, $y);
        $this->cell(195, 20, '', 1, 1);
        $this->cell(4, 2, '', 0, 1);
    }

    private function addInfoAtendimentos($atendimentos)
    {
        $alturaNecessaria = 13 + $this->alturaNecessariaCid($atendimentos);
        if ($this->getAvailableHeight() < $alturaNecessaria) {
            $this->addPage();
        }

        $this->addCabecalhoAtendimento();
        $linhaImpressa = 0;
        foreach ($atendimentos as $atendimento) {
            $alturaNecessaria = 13 + $this->alturaNecessariaCid($atendimento);
            if ($this->getAvailableHeight() < $alturaNecessaria) {
                $this->addPage();
                $this->addCabecalhoAtendimento();
                $linhaImpressa = 0;
            }

            $linhaImpressa++;
            $cor = !($linhaImpressa % 2);
            $this->addLinha($atendimento, $cor);
        }
        $this->cell(4, 4, '', 0, 1);
    }

    private function addCabecalhoAtendimento()
    {
        $this->setFont('ARIAL', 'B', 8);
        $this->cell(15, 5, 'ATEND.', 1, 0, 'C', true);
        $this->cell(15, 5, 'DATA', 1, 0, 'C', true);
        $this->cell(50, 5, 'UNIDADE', 1, 0, 'C', true);
        $this->cell(50, 5, 'EQUIPE', 1, 0, 'C', true);
        $this->cell(50, 5, 'PROFISSIONAL', 1, 0, 'C', true);
        $this->cell(15, 5, 'CBO', 1, 1, 'C', true);
    }

    private function addLinha($atendimento, $cor)
    {
        $this->setFont('ARIAL', '', 7);
        $this->cell(15, 4, $atendimento->id, 1, 0, 'C', $cor);
        $this->cell(15, 4, $atendimento->data, 1, 0, 'C', $cor);
        $this->cellAdapt(7, 50, 4, $atendimento->unidade, 1, 0, 'C', $cor);
        $this->cellAdapt(7, 50, 4, $atendimento->equipe, 1, 0, 'C', $cor);
        $this->cellAdapt(7, 50, 4, $atendimento->profissional, 1, 0, 'C', $cor);
        $this->cellAdapt(7, 15, 4, $atendimento->cbo, 1, 1, 'C', $cor);
        
        $x = $this->getX();
        $y = $this->getY();
        $altura = 4 + $this->alturaNecessariaCid($atendimento);
        $this->cell(195, $altura, '', 1, 0, '', $cor);
        $this->setXY($x, $y);
        $this->cell(11, 4, 'CIAP2:', 0, 0, 'L', $cor);
        $this->cell(95, 4, $atendimento->ciap2, 0, 0, 'L', $cor);
        $this->cell(4, 4, 'IG:', 0, 0, 'L', $cor);
        $this->cell(85, 4, $atendimento->ig, 0, 1, 'L', $cor);
        $this->cell(8, 4, 'CID:', 0, 0, 'L', $cor);
        $this->multiCell(187, 4, $atendimento->cids, 0, 'L', $cor);
        $this->setXY($x, $y);
        $this->cell(195, $altura, '', 1, 1);
    }

    private function alturaNecessariaCid($atendimento)
    {
        if (is_array($atendimento)) {
            $atendimento = $atendimento[0];
        }

        return $this->nbLines(187, $atendimento->cids) * 4;
    }
}
