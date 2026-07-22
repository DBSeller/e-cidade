<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios;

use ECidade\Pdf\Pdf;
use DBException;

class RelatorioTcePDF extends Pdf
{
    private $dadosDespesas;
    private $adicaoAuditoria;
    private $exclusaoAuditoria;
    private $resto;
    private $mes;
    private $ano;
    private $valorEmpenhadoDespesaMDE;
    private $valorLiquidadoDespesaMDE;
    private $resultadosLiquidoFundeb;

    private $despesaEmpenhada;
    private $despesaLiquidada;

    private $percentualRelacaoDespesaEmpenhada;
    private $percentualRelacaoDespesaLiquidada;

    public function __construct($dados, $mde)
    {
        parent::__construct();

        $this->adicaoAuditoria = $dados['adicaoAuditoria'];
        $this->exclusaoAuditoria = $dados['exclusaoAuditoria'];
        $this->resto = $dados['resto'];
        $this->mes = $dados['mes'];
        $this->ano = $dados['anousu'];
        $this->dadosDespesas = [
            "despesas_empenhadas_com_recursos_do_fundeb" => 0,
            "despesas_liquidadas_com_recursos_do_fundeb" => 0,
            "despesas_empenhadas_com_recursos_de_impostos" => 0,
            "despesas_liquidadas_com_recursos_de_impostos" => 0,
            "total_das_transferencias_recebidas_do_fundeb" => 0,
            "total_das_deducoes_do_fundeb" => 0,
            "total_da_receita_de_complementacao_da_uniao" => 0,
            "total_das_receitas_com_impostos_e_transferencias" => 0
        ];

        foreach ($mde as $dado) {
            $this->dadosDespesas[$dado->titulo] += $dado->valor_ate_o_periodo;
        }

        //5 -> (1+3)
        $this->valorEmpenhadoDespesaMDE = $this->dadosDespesas["despesas_empenhadas_com_recursos_do_fundeb"]
            + $this->dadosDespesas["despesas_empenhadas_com_recursos_de_impostos"];
        //6 -> (2+4)
        $this->valorLiquidadoDespesaMDE = $this->dadosDespesas["despesas_liquidadas_com_recursos_do_fundeb"]
            + $this->dadosDespesas["despesas_liquidadas_com_recursos_de_impostos"];
        //9 -> (5)
        $this->resultadosLiquidoFundeb = ($this->dadosDespesas["total_das_transferencias_recebidas_do_fundeb"]
            + $this->dadosDespesas["total_das_deducoes_do_fundeb"]);

        //12 -> (5+7-8-9-10-11)
        $this->despesaEmpenhada = $this->valorEmpenhadoDespesaMDE + $this->adicaoAuditoria -
            $this->exclusaoAuditoria - $this->resultadosLiquidoFundeb -
            $this->dadosDespesas["total_da_receita_de_complementacao_da_uniao"] - $this->resto;
        //13 -> (6+7-8-9-10-11)
        $this->despesaLiquidada = $this->valorLiquidadoDespesaMDE + $this->adicaoAuditoria -
            $this->exclusaoAuditoria - $this->resultadosLiquidoFundeb -
            $this->dadosDespesas["total_da_receita_de_complementacao_da_uniao"] - $this->resto;

        $this->percentualRelacaoDespesaEmpenhada = ($this->despesaEmpenhada
            / $this->dadosDespesas["total_das_receitas_com_impostos_e_transferencias"]) * 100;

        $this->percentualRelacaoDespesaLiquidada = ($this->despesaLiquidada
                / $this->dadosDespesas["total_das_receitas_com_impostos_e_transferencias"]) * 100;


        $this->addTitulo('Relatório Gerencial de Aplicação em MDE.');
    }

    public function emitir()
    {
        $this->initPdf();
        $this->addPage();
        $this->montaCabecalho();
        $this->imprimeTopico('Despesas em MDE');
        $this->imprimeSubTopico(
            'Despesas custeadas com recursos do FUNDEB',
            $this->dadosDespesas["despesas_empenhadas_com_recursos_do_fundeb"],
            $this->dadosDespesas["despesas_liquidadas_com_recursos_do_fundeb"]
        );
        $this->imprimeSubTopico(
            'Despesas custeadas com recursos de Impostos',
            $this->dadosDespesas["despesas_empenhadas_com_recursos_de_impostos"],
            $this->dadosDespesas["despesas_liquidadas_com_recursos_de_impostos"]
        );
        $this->imprimeSubTopico(
            'Total das Despesas em MDE',
            $this->valorEmpenhadoDespesaMDE,
            $this->valorLiquidadoDespesaMDE
        );
        $this->imprimeTopico('Deduções e/ou Adições');
        $this->imprimeCorpoDecucoesAdicoes();
        $this->imprimeTopico('Total das Aplicações em MDE');
        $this->imprimeLinhaComValor('Considerando a Despesa Empenhada', $this->despesaEmpenhada);
        $this->imprimeLinhaComValor('Considerando a Despesa Liquidada', $this->despesaLiquidada);
        $this->imprimeTopicoComValor(
            'Total das Receitas de Impostos e Transferências',
            $this->dadosDespesas["total_das_receitas_com_impostos_e_transferencias"]
        );
        $this->imprimeTopico('Percentual de Aplicação em MDE');
        $this->imprimeLinhaPorcentagem('Em relação à Despesa Empenhada', $this->percentualRelacaoDespesaEmpenhada);
        $this->imprimeLinhaPorcentagem('Em relação à Despesa Liquidada', $this->percentualRelacaoDespesaLiquidada);

        return $this->imprimir();
    }

    public function imprimir()
    {
        $fileName = 'tmp/relatorio_gerencial_mde' . time() . '.pdf';
        $this->output('F', $fileName);

        return [
            "name" => "Relatório Gerencial de Aplicação em MDE",
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
        $this->addTitulo('Período: '. str_pad($this->mes, 2, "0", STR_PAD_LEFT) . '/'.$this->ano);
    }

    /**
     * Adaptar
     */
    private function montaCabecalho()
    {
        $this->setFont('ARIAL', 'B', 8);
        $this->cell(195, 4, 'APLICAÇÕES EM MDE', 0, 1, 'C');
    }

    private function imprimeTopico($topico)
    {
        $this->setFont('ARIAL', '', 8);
        $this->cell(195, 4, $topico, 1, 1, 'L', 1);
    }

    private function imprimeTopicoComValor($topico, $valor)
    {
        $this->setFont('ARIAL', '', 8);
        $this->cell(195, 4, $topico, 1, 0, 'L', 1);
        $this->setX(160);
        $this->cell(20, 4, db_formatar($valor, 'f'), 0, 1, 'R');
    }
    private function imprimeSubTopico($subtopico, $valorEmpenhado, $valorLiquidado)
    {
        $this->setFont('ARIAL', '', 8);
        $this->setX(10);
        $this->cell(30, 4, $subtopico, 0, 1, 'L');
        $this->setX(15);
        $this->cell(20, 4, 'Valores Empenhados', 0, 0, 'L');
        $this->setX(160);
        $this->cell(20, 4, db_formatar($valorEmpenhado, 'f'), 0, 1, 'R');
        $this->setX(15);
        $this->cell(20, 4, 'Valores Liquidados', 0, 0, 'L');
        $this->setX(160);
        $this->cell(20, 4, db_formatar($valorLiquidado, 'f'), 0, 1, 'R');
    }

    private function imprimeCorpoDecucoesAdicoes()
    {
        $this->imprimeLinhaComValor('Adições da Auditoria', $this->adicaoAuditoria);
        $this->imprimeLinhaComValor('Exclusões da Auditoria', $this->exclusaoAuditoria);
        $this->imprimeLinhaComValor('Resultado Líquido das Transferências do FUNDEB', $this->resultadosLiquidoFundeb);
        $this->imprimeLinhaComValor(
            'Dedução da Receita proveniente da Complementação da União',
            $this->dadosDespesas["total_da_receita_de_complementacao_da_uniao"]
        );
        $this->imprimeLinhaComValor(
            'Restos a Pagar Inscritos no Exercício sem Disponibilidade Financeira de Recursos do MDE',
            $this->resto
        );
    }

    private function imprimeLinhaComValor($linha, $valor)
    {
        $this->setX(10);
        $this->cell(20, 4, $linha, 0, 0, 'L');
        $this->setX(160);
        $this->cell(20, 4, db_formatar($valor, 'f'), 0, 1, 'R');
    }

    private function imprimeLinhaPorcentagem($linha, $valor)
    {
        $this->setX(10);
        $this->cell(20, 4, $linha, 0, 0, 'L');
        $this->setX(160);
        $this->cell(20, 4, db_formatar($valor, 'f').'%', 0, 1, 'R');
    }
}
