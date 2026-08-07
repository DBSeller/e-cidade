<?php

namespace App\Domain\Financeiro\Contabilidade\Services\Relatorios\LRF\RREO\AnexoTres;

use App\Domain\Financeiro\Contabilidade\Factories\TemplateFactory;
use App\Domain\Financeiro\Contabilidade\Relatorios\LRF\RREO\XlsAnexoTres;
use Exception;

class AnexoTresInRsService extends AnexoTresService
{
    protected $sections = [
        'receitas' => [1, 38],
    ];

    /**
     * Mapa das linhas que totaliza outras linhas
     * @var \int[][]
     */
    protected $totalizar = [
        1 => [3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14, 16, 17, 18, 19, 20, 21, 22, 23, 24],
        2 => [3, 4, 5, 6, 7],
        9 => [10, 11],
        25 => [26, 27, 28, 29, 30, 31],
    ];

    protected $linhasSimplificado = [
        34 => 'Receita Corrente Líquida',
        36 => 'Receita Corrente Líquida Ajustada para Cálculo dos Limites de Endividamento',
        38 => 'Receita Corrente Líquida Ajustada para Cálculo dos Limites da Despesa com Pessoal',
    ];

    protected $linhasSimplificadoCompleto = [
        34 => 'Receita Corrente Líquida',
        35 => '(-) Transferências obrigatórias da União relativas às emendas individuais (art. 166-A, § 1o, da CF)',
        36 => 'Receita Corrente Líquida Ajustada para Cálculo dos Limites de Endividamento',
        37 => '(-) Transferências obrigatórias da União relativas às emendas de bancada (art. 166, § 16, da CF)',
        38 => 'Receita Corrente Líquida Ajustada para Cálculo dos Limites da Despesa com Pessoal',
    ];

    /**
     * Linhas que deve retornar para o Anexo I da RGF
     * @var int[]
     */
    protected $linhasApuracaoCumprimentoLegal = [
        34 => 'RCL',
        35 => 'Emenda individual',
        37 => 'Emenda de bancada'
    ];

    /**
     * Linhas que devem ser positivas mesmo que o sinal seja negativo.
     * @var int[]
     */
    protected $linhasAplicarAbs = [33];

    /**
     * @param $filtros
     * @throws Exception
     */
    public function __construct($filtros)
    {
        $template = TemplateFactory::getTemplate(
            $filtros['codigo_relatorio'],
            $filtros['periodo'],
            TemplateFactory::MODELO_IN_RS
        );
        $this->parser = new XlsAnexoTres($template);
        parent::__construct($filtros);
    }

    protected function posTotalizarLinhas()
    {
        $this->calcularLinha32();
        $this->calcularLinha34(); // soma
        $this->calcularLinha36();
        $this->calcularLinha38();
    }

    /**
     * Calculo da linha 32 III - SUBTOTAL (I - II)
     * @throws Exception
     */
    private function calcularLinha32()
    {
        $this->calculaLinhaSubtracao(32, 1, 25);
    }

    /**
     * Calculo da linha 34 V - RECEITA CORRENTE LÍQUIDA (V = III + IV)
     * @throws Exception
     */
    private function calcularLinha34()
    {
        $this->calculaLinhaSoma(34, 32, 33);
    }

    /**
     * Calculo da linha 36 VII - RECEITA CORRENTE LÍQUIDA (Endividamento) (VII = V - VI)
     * @throws Exception
     */
    private function calcularLinha36()
    {
        $this->calculaLinhaSubtracao(36, 34, 35);
    }

    /**
     * Calculo da linha 38 IX - RECEITA CORRENTE LÍQUIDA (Despesas com Pessoal) (IX = VII - VIII)
     * @throws Exception
     */
    private function calcularLinha38()
    {
        $this->calculaLinhaSubtracao(38, 36, 37);
    }

    public function getLinhaEmendaIndividuais()
    {
        if (empty($this->linhas)) {
            $this->processar();
        }

        return $this->linhas[35];
    }
}
