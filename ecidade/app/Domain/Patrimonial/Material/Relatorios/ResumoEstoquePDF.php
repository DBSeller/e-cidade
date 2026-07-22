<?php

namespace App\Domain\Patrimonial\Material\Relatorios;

use ECidade\Patrimonial\Material\Helpers\Material;
use ECidade\Patrimonial\Material\Models\Deposito;
use ECidade\Pdf\Pdf;
use stdClass;

class ResumoEstoquePDF extends Pdf
{
    const SINTETICO = 1;

    /**
     * @var stdClass
     */
    private $dados;
    private $agruparPorConta;
    private $agruparPorGrupo;
    private $tipoImpressao;
    /**
     * @var Deposito[]
     */
    private $depositos;

    public function __construct($dados, $configuracoes = null)
    {
        parent::__construct('L');
        $this->dados = $dados;
        $this->addTitulo("");
        $this->addTitulo("RESUMO CONTÁBIL DE ESTOQUE");
        $this->addTitulo("");
        if (!empty($configuracoes->dataInicial) && !empty($configuracoes->dataFinal)) {
            $this->addTitulo("De {$configuracoes->dataInicial} Até {$configuracoes->dataFinal}");
        } elseif (!empty($configuracoes->dataInicial)) {
            $this->addTitulo("Apartir de {$configuracoes->dataInicial}");
        } elseif (!empty($configuracoes->dataFinal)) {
            $this->addTitulo("Até {$configuracoes->dataFinal}");
        }

        if ($configuracoes->exibirTransferencias) {
            $this->addTitulo("Exibir transferências: Sim");
        } else {
            $this->addTitulo("Exibir transferências: Não");
        }

        if ($configuracoes->exibirMateriaisSemEstoque) {
            $this->addTitulo("Somente materiais com saldo: Não");
        } else {
            $this->addTitulo("Somente materiais com saldo: Sim");
        }

        if ($configuracoes->ordem == 1) {
            $this->addTitulo("Ordenar por: Descrição do Material");
        } else {
            $this->addTitulo("Ordenar por: Código do Material");
        }

        $this->agruparPorConta = $configuracoes->agruparPorConta;
        $this->agruparPorGrupo = $configuracoes->agruparPorGrupo;
        $this->tipoImpressao = $configuracoes->tipoImpressao;
        $this->depositos = $configuracoes->depositos;
    }

    protected function initPdf()
    {
        $this->mostrarRodape(true);
        $this->mostrarTotalDePaginas(true);
        $this->SetMargins(10, 8, 8);
        $this->setFillColor(230);
        $this->SetAutoPageBreak(true, 15);
        $this->exibeHeader(true);
        $this->setExibeBrasao(true);
    }

    protected function imprimir()
    {
        $fileName = 'tmp/resumo_estoque_' . time() . '.pdf';
        $this->Output($fileName, 'F');
        return [
            "name" => "Relatório Controle de Estoque PDF",
            "path" => $fileName,
            "file" => ECIDADE_REQUEST_PATH . $fileName
        ];
    }

    public function emitirPdf()
    {
        $this->initPdf();
        $this->AddPage();

        if ($this->agruparPorConta) {
            foreach ($this->dados->contas as $conta) {
                $this->imprimirDadosConta($conta);

                if ($this->agruparPorGrupo) {
                    foreach ($conta->grupos as $grupo) {
                        $this->imprimirDadosGrupo($grupo);
                        $this->imprimirMateriais($grupo->materiais);
                        $this->imprimirTotaisGrupo($grupo);
                    }
                    continue;
                }
                $this->imprimirMateriais($conta->materiais);
            }
            $this->imprimirDepositos();
            $this->imprimirTotalizador($this->dados->total_geral);
            return $this->imprimir();
        }

        if ($this->agruparPorGrupo) {
            foreach ($this->dados->grupos as $grupo) {
                $this->imprimirDadosGrupo($grupo);
                $this->imprimirMateriais($grupo->materiais);
                $this->imprimirTotaisGrupo($grupo);
            }
            $this->imprimirDepositos();
            $this->imprimirTotalizador($this->dados->total_geral);
            return $this->imprimir();
        }

        $this->imprimirMateriais($this->dados->materiais);
        $this->imprimirDepositos();
        $this->imprimirTotalizador($this->dados->total_geral);
        return $this->imprimir();
    }

    private function formatarValor($valor, $decimais = 2)
    {
        $valorFormatado = number_format($valor, $decimais, ',', '.');
        if ($valorFormatado == '-0,00') {
            $valorFormatado = '0,00';
        }
        return "R$ " . $valorFormatado;
    }

    private function imprimirCabecalhoMateriais()
    {
        $this->SetFont('arial', 'b', 6);
        $this->Cell(18, 5, "Cód. do Material", "TBR", 0, "C", 0);
        $this->Cell(62, 5, "Nome do Material", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Saldo Inicial", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Qtd. Inicial", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Entradas", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Qtd. Entradas", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Saídas", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Qtd. Saída", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Saldo Final", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Qtd. Estoque", "TB", 1, "C", 0);
    }

    private function imprimirMateriais($materiais)
    {
        if ($this->tipoImpressao == self::SINTETICO) {
            return;
        }
        $this->imprimirCabecalhoMateriais();
        $this->SetFont('arial', '', 6);
        $this->setFillColor(255);
        $color = 255;
        foreach ($materiais as $material) {
            if ($this->getAvailableHeight() < 4) {
                $this->addPage();
                $this->setFillColor(255);
                $color = 255;
                $this->imprimirCabecalhoMateriais();
                $this->SetFont('arial', '', 6);
            }
            if ($color == 255) {
                $color = 243;
            } else {
                $color = 255;
            }
            $this->setFillColor($color);
            $this->Cell(18, 4, $material->codigo, "TBR", 0, "C", 1);
            $this->Cell(62, 4, substr($material->descricao, 0, 50), "TBR", 0, "L", 1);
            $this->Cell(25, 4, $this->formatarValor($material->valor_anterior), "TBR", 0, "R", 1);
            $quantidade_anterior = Material::arredondarQuantidade($material->quantidade_anterior);
            $this->Cell(25, 4, $quantidade_anterior, "TBR", 0, "C", 1);
            $this->Cell(25, 4, $this->formatarValor($material->valor_entradas), "TBR", 0, "R", 1);
            $quantidade_entrada = Material::arredondarQuantidade($material->quantidade_entrada);
            $this->Cell(25, 4, $quantidade_entrada, "TBR", 0, "C", 1);
            $this->Cell(25, 4, $this->formatarValor($material->valor_saidas), "TBR", 0, "R", 1);
            $quantidade_saida = Material::arredondarQuantidade($material->quantidade_saida);
            $this->Cell(25, 4, $quantidade_saida, "TBR", 0, "C", 1);
            $this->Cell(25, 4, $this->formatarValor($material->saldo_final), "TBR", 0, "R", 1);
            $this->Cell(25, 4, Material::arredondarQuantidade($material->quantidade_final), "TB", 1, "C", 1);
        }
        $this->setFillColor(240);
    }

    private function imprimirDadosConta($conta)
    {
        $this->SetFont('arial', 'b', 7);
        $this->cell(40, 5, 'Reduzido: ' . $conta->codigo, 'TB', 0, 'L', 1);
        $this->cell(60, 5, 'Estrutural: ' . $conta->estrutural, 'TB', 0, 'L', 1);
        $this->cell(180, 5, 'Descrição da Conta: ' . $conta->descricao, 'TB', 1, 'L', 1);

        $this->imprimirCabecalhoSintetico();

        $this->Cell(18, 5, "", "B", 0, "C", 1);
        $this->Cell(62, 5, "Total da Conta: ", "BR", 0, "R", 1);
        $this->Cell(25, 5, $this->formatarValor($conta->total_geral['valor_anterior']), "BR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($conta->total_geral['quantidade_anterior']), "BR", 0, "C", 1);
        $this->Cell(25, 5, $this->formatarValor($conta->total_geral['valor_entradas']), "BR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($conta->total_geral['quantidade_entrada']), "BR", 0, "C", 1);
        $this->Cell(25, 5, $this->formatarValor($conta->total_geral['valor_saidas']), "BR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($conta->total_geral['quantidade_saida']), "BR", 0, "C", 1);
        $this->Cell(25, 5, $this->formatarValor($conta->total_geral['saldo_final']), "BR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($conta->total_geral['quantidade_final']), "B", 1, "C", 1);

        $this->cell(50, 1, '', 0, 1);
    }

    private function imprimirDadosGrupo($grupo)
    {
        if ($this->getAvailableHeight() < 10) {
            $this->addPage();
        }
        $this->SetFont('arial', 'b', 7);
        $this->cell(18, 5, $grupo->estrutural, 'TB', 0, 'L', 1);
        $this->cell(262, 5, 'Descrição do Grupo: ' . $grupo->descricao, 'TB', 1, 'L', 1);

        if ($this->tipoImpressao == self::SINTETICO) {
            $this->imprimirCabecalhoSintetico();
        }
    }

    private function imprimirTotaisGrupo($grupo)
    {
        $this->SetFont('arial', 'b', 7);
        $this->Cell(18, 5, "", "B", 0, "C", 1);
        $this->Cell(62, 5, "Total do Grupo: ", "BR", 0, "R", 1);
        $this->Cell(25, 5, $this->formatarValor($grupo->total_geral['valor_anterior']), "BR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($grupo->total_geral['quantidade_anterior']), "BR", 0, "C", 1);
        $this->Cell(25, 5, $this->formatarValor($grupo->total_geral['valor_entradas']), "BR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($grupo->total_geral['quantidade_entrada']), "BR", 0, "C", 1);
        $this->Cell(25, 5, $this->formatarValor($grupo->total_geral['valor_saidas']), "BR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($grupo->total_geral['quantidade_saida']), "BR", 0, "C", 1);
        $this->Cell(25, 5, $this->formatarValor($grupo->total_geral['saldo_final']), "BR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($grupo->total_geral['quantidade_final']), "B", 1, "C", 1);
        $this->cell(280, 3, '', 0, 1);
    }

    private function imprimirDepositos()
    {
        if (empty($this->depositos)) {
            return;
        }
        if ($this->getAvailableHeight() < 15) {
            $this->addPage();
        }
        $this->SetFont('arial', 'b', 7);
        $this->cell(140, 5, 'Depósitos ', 'TB', 1, 'C', 1);
        $this->SetFont('arial', '', 7);
        foreach ($this->depositos as $deposito) {
            $string = $deposito->getCodigo() . ' - ' . $deposito->getDepartamento()->getNomeDepartamento();
            $this->cell(10, 5, '', 'TB', 0, 'L');
            $this->cell(130, 5, $string, 'TB', 1, 'L');
        }
    }

    private function imprimirTotalizador($total)
    {
        $this->cell(280, 3, '', 0, 1);
        $this->SetFont('arial', 'b', 7);
        $this->Cell(38, 5, "Total de itens: {$total['total_itens']}", "TB", 0, "L", 1);
        $this->Cell(42, 5, "Total geral: ", "TBR", 0, "R", 1);
        $this->Cell(25, 5, $this->formatarValor($total['valor_anterior']), "TBR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($total['quantidade_anterior']), "TBR", 0, "C", 1);
        $this->Cell(25, 5, $this->formatarValor($total['valor_entradas']), "TBR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($total['quantidade_entrada']), "TBR", 0, "C", 1);
        $this->Cell(25, 5, $this->formatarValor($total['valor_saidas']), "TBR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($total['quantidade_saida']), "TBR", 0, "C", 1);
        $this->Cell(25, 5, $this->formatarValor($total['saldo_final']), "TBR", 0, "C", 1);
        $this->Cell(25, 5, Material::arredondarQuantidade($total['quantidade_final']), "TB", 1, "C", 1);
        $this->cell(280, 3, '', 0, 1);
    }

    private function imprimirCabecalhoSintetico()
    {
        $this->SetFont('arial', 'b', 6);
        $this->Cell(80, 5, "", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Saldo Inicial", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Qtd. Inicial", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Entradas", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Qtd. Entradas", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Saídas", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Qtd. Saída", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Saldo Final", "TBR", 0, "C", 0);
        $this->Cell(25, 5, "Qtd. Estoque", "TB", 1, "C", 0);
    }
}
