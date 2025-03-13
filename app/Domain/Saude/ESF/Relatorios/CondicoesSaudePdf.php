<?php

namespace App\Domain\Saude\ESF\Relatorios;

use ECidade\Enum\Common\FaixaEtariaEnum;
use ECidade\Enum\Saude\ESF\SituacaoCondicaoPacienteEnum;
use ECidade\Pdf\Pdf;
use Exception;
use stdClass;

class CondicoesSaudePdf extends Pdf
{
    /**
     * @var array
     */
    private $dados;

    private $isDeficiencia;

    /**
     * @param array $dados
     * @param stdClass $filtros
     * @throws Exception
     */
    public function __construct(array $dados, stdClass $filtros)
    {
        parent::__construct();
        $this->dados = $dados;
        $this->isDeficiencia = $filtros->condicao == SituacaoCondicaoPacienteEnum::DEFICIENCIA;
        $this->setTitulo($filtros);
    }

    public function emitir()
    {
        $this->initPdf();
        $this->addPage();
        $total = 0;
        $totalDeparmento = 0;
        $departamento = reset($this->dados)->departamento;
        $totalQuebra = count($this->dados);
        foreach ($this->dados as $index => $agrupamento) {
            $totalMicroarea = count($agrupamento->pacientes);
            $totalDeparmento += $totalMicroarea;
            $total += $totalMicroarea;

            $imprimiTotal = $totalQuebra == $index + 1;
            $imprimiTotalDepartamento = 0;
            if (($imprimiTotal && $totalDeparmento != $total)
                || (isset($this->dados[$index + 1]) && $this->dados[$index + 1]->departamento != $departamento)
            ) {
                $departamento = isset($this->dados[$index + 1])
                    ? $this->dados[$index + 1]->departamento
                    : $agrupamento->departamento;
                $imprimiTotalDepartamento = 1;
            }

            $tamanhoMaximo = 14;
            if ($totalQuebra > 1) {
                $tamanhoMaximo += $totalMicroarea == 1 ? ($imprimiTotalDepartamento ? 8 : 4) : 0;
            }

            if ($this->getAvailableHeight() < $tamanhoMaximo) {
                $this->addPage();
            }
            $this->montaCabecalho($agrupamento);
            $linha = 0;
            foreach ($agrupamento->pacientes as $key => $paciente) {
                $tamanhoMaximo = 4;
                if ($key + $imprimiTotalDepartamento + 1 == $totalMicroarea) {
                    $tamanhoMaximo = $imprimiTotalDepartamento ? 12 : 8;
                    $tamanhoMaximo += $imprimiTotal ? 4 : 0;
                }

                if ($this->getAvailableHeight() < $tamanhoMaximo) {
                    $this->addPage();
                    $this->montaCabecalho($agrupamento);
                    $linha = 0;
                }

                $linha++;
                $this->montaLinha($paciente, !($linha % 2));
            }

            $this->setFont('Arial', 'B', 7);
            if ($totalQuebra > 1) {
                $this->cell(194, 4, "Total desta microárea: {$totalMicroarea}", 0, 1, 'R');
                if ($imprimiTotalDepartamento) {
                    $this->cell(194, 4, "Total desta unidade: {$totalDeparmento}", 0, 1, 'R');
                    $totalDeparmento = 0;
                }
            }
            if (!$imprimiTotal) {
                $this->cell(1, 4, '', 0, 1);
            }
        }
        $this->cell(194, 4, "Total geral: {$total}", 0, 1, 'R');

        return $this->imprimir();
    }

    /**
     * @param stdClass $filtros
     * @throws Exception
     */
    private function setTitulo(stdClass $filtros)
    {
        $this->addTitulo('Condições de Saúde e Faixa Etária');
        $data = new \DateTime($filtros->data);
        $this->addTitulo("Data: {$data->format('d/m/Y')}");

        $filtrosSelecionados = [];
        $unidade = $filtros->unidade ? reset($this->dados)->departamento : 'TODAS';
        $filtrosSelecionados[] = "Unidade: {$unidade}";
        $equipe = $filtros->equipe ? reset($this->dados)->equipe : 'TODAS';
        $filtrosSelecionados[] = "Equipe: {$equipe}";
        $microarea = $filtros->microarea ? reset($this->dados)->microarea : 'TODAS';
        $filtrosSelecionados[] = "Microárea: {$microarea}";
        $sexo = $filtros->sexo ? ($filtros->sexo === 'M' ? 'MASCULINO' : 'FEMININO') : 'AMBOS';
        $filtrosSelecionados[] = "Sexo: {$sexo}";
        $this->addTitulo(implode(', ', $filtrosSelecionados));
        $this->addTitulo('');
        $filtrosSelecionados = [];
        $condicao = $filtros->condicao
            ? (new SituacaoCondicaoPacienteEnum((int)$filtros->condicao))->name()
            : 'SEM CONDIÇÃO';
        $filtrosSelecionados[] = "Situação/Condição: {$condicao}";
        $faixaEtaria = $filtros->faixaEtaria
            ? (new FaixaEtariaEnum((int)$filtros->faixaEtaria))->name()
            : 'TODAS';
        $filtrosSelecionados[] = "Faixa Etária: {$faixaEtaria}";
        $this->addTitulo(implode(', ', $filtrosSelecionados));
    }

    private function initPdf()
    {
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);
        $this->setMargins(8, 8, 8);
        $this->setAutoPageBreak(false, 10);
        $this->aliasNbPages();
        $this->setFillColor(235);
        $this->setFont('Arial', 'B', 9);
        $this->exibeHeader(true);
    }

    private function montaCabecalho(stdClass $agrupamento)
    {
        $this->setFont('Arial', 'B', 7);
        $this->cell(65, 5, $agrupamento->departamento, 1, 0, 'C');
        $this->cell(80, 5, $agrupamento->equipe, 1, 0, 'C');
        $this->cell(50, 5, $agrupamento->microarea, 1, 1, 'C');
        $this->setFillColor(210);
        $this->setFont('Arial', 'B', 8);
        if ($this->isDeficiencia) {
            $this->cell(140, 5, 'Paciente', 1, 0, 'C', 1);
            $this->cell(25, 5, 'Sexo', 1, 0, 'C', 1);
            $this->cell(30, 5, 'Deficiência', 1, 1, 'C', 1);
        } else {
            $this->cell(170, 5, 'Paciente', 1, 0, 'C', 1);
            $this->cell(25, 5, 'Sexo', 1, 1, 'C', 1);
        }
    }

    private function montaLinha(stdClass $paciente, $cor)
    {
        $this->setFont('Arial', '', 7);
        if ($this->isDeficiencia) {
            $this->cell(140, 4, "{$paciente->id} - {$paciente->nome}", 0, 0, '', $cor);
            $this->cell(25, 4, $paciente->sexo, 0, 0, 'C', $cor);
            $this->cell(30, 4, $paciente->deficiencia, 0, 1, '', $cor);
        } else {
            $this->cell(170, 4, "{$paciente->id} - {$paciente->nome}", 0, 0, '', $cor);
            $this->cell(25, 4, $paciente->sexo, 0, 1, 'C', $cor);
        }
    }

    private function imprimir()
    {
        $nomeArquivo = 'tmp/condicoessaude' . time() . '.pdf';
        $this->output('F', $nomeArquivo);

        return [
            "name" => "Relatório Condições de Saúde e Faixa Etária",
            "path" => $nomeArquivo,
            'pathExterno' => ECIDADE_REQUEST_PATH . $nomeArquivo
        ];
    }
}
