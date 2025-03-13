<?php

namespace App\Domain\Financeiro\Contabilidade\Services\Relatorios\LRF\RREO\AnexoTres;

class AnexoTresMdf2022Service extends AnexoTresMdfService
{
    protected $sections = [
        'receitas' => [1, 33],
    ];

    /**
     * Mapa das linhas que totaliza outras linhas
     * @var \int[][]
     */
    protected $totalizar = [
        1 => [3, 4, 5, 6, 7, 8, 10, 11, 12, 13, 14, 16, 17, 18, 19, 20, 21, 22, 23, 24],
        2 => [3, 4, 5, 6, 7],
        9 => [10, 11],
        15 => [16, 17, 18, 19, 20, 21, 22, 23, 24],
        25 => [26, 27, 28, 29],
    ];

    protected $linhasSimplificado = [
        30 => 'Receita Corrente Líquida',
        32 => 'Receita Corrente Líquida Ajustada para Cálculo dos Limites de Endividamento',
        34 => 'Receita Corrente Líquida Ajustada para Cálculo dos Limites da Despesa com Pessoal',
    ];

    protected $linhasSimplificadoCompleto = [
        30 => 'Receita Corrente Líquida',
        31 => '(-) Transferências obrigatórias da União relativas às emendas individuais (art. 166-A, § 1o, da CF)',
        32 => 'Receita Corrente Líquida Ajustada para Cálculo dos Limites de Endividamento',
        33 => '(-) Transferências obrigatórias da União relativas às emendas de bancada (art. 166, § 16, da CF)',
        34 => 'Receita Corrente Líquida Ajustada para Cálculo dos Limites da Despesa com Pessoal',
    ];

    /**
     * Linhas que devem ser positivas mesmo que o sinal seja negativo.
     * @var int[]
     */
    protected $linhasAplicarAbs = [29];

    /**
     * Linhas que deve retornar para o Anexo I da RGF
     * @var int[]
     */
    protected $linhasApuracaoCumprimentoLegal = [
        30 => 'RCL',
        31 => 'Emenda individual',
        33 => 'Emenda de bancada'
    ];

    protected $linhaRcl = 30;

    protected function posTotalizarLinhas()
    {
        // realiza as subtraçoes
        $this->calcularLinhaRCL();

        // calcula as linhas 32 e 34
        // RECEITA CORRENTE LÍQUIDA AJUSTADA PARA CÁLCULO DOS LIMITES DE ENDIVIDAMENTO (V) = (III - IV)
        $this->calculaLinhaSubtracao(32, 30, 31);

        // RECEITA CORRENTE LÍQUIDA AJUSTADA PARA CÁLCULO DOS LIMITES DA DESPESA COM PESSOAL (VII) = (V - VI)
        $this->calculaLinhaSubtracao(34, 32, 33);
    }

    /**
     * @return \stdClass
     */
    public function getLinhaEmendaIndividuais()
    {
        if (empty($this->linhas)) {
            $this->processar();
        }

        return $this->linhas[31];
    }
}
