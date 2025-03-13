<?php


namespace App\Domain\Financeiro\Contabilidade\Relatorios\Balancete\Despesa;

class BalanceteDespesaSintetico extends BalanceteDespesa
{
    /**
     * De para do nível para oo nome do que esta sendo impresso.
     * @var string[]
     */
    private $deParaClassificacao = [
        'orgao' => 'Órgão',
        'unidade' => 'Unidade',
        'funcao' => 'Função',
        'subfuncao' => 'Subfunção',
        'programa' => 'Programa',
        'projeto' => 'Projeto/Atividade',
        'elemento' => 'Elementos',
        'recurso' => 'Recursos',
    ];

    /**
     * @var string
     */
    protected $modelo = 'SINTÉTICO';

    protected function imprimir()
    {
        $this->imprimeCabecalho();
        foreach ($this->dados as $dado) {
            $this->imprimeClassificacao($dado);
        }

        $this->imprimeTotalizador($this->totalizador);

        $this->imprimirAssinaturas();
    }

    /**
     * Função recursiva que imprime os dados do relatório
     * @param $dado
     */
    private function imprimeClassificacao($dado)
    {
        $classificacao = $this->deParaClassificacao[$dado->nivel];
        $descricao = sprintf('%s: %s - %s', $classificacao, $dado->formatado, $dado->descricao);
        $this->bold();
        $this->Cell($this->wLinhaP, $this->hLinha, $descricao, 0, 1);
        $this->regular();
        if (!empty($dado->filho)) {
            foreach ($dado->filho as $proximoNivel) {
                $this->imprimeClassificacao($proximoNivel);
            }
        } else {
            $this->imprimeRecursos($dado->recursos);
            $this->imprimeSaldos($dado->valores);
        }

        $mensagem = sprintf(
            'Total %s: %s - %s:',
            $this->deParaClassificacao[$dado->nivel],
            $dado->formatado,
            $dado->descricao
        );

        $this->imprimeTotalizador($dado->valores, $mensagem);
    }
}
