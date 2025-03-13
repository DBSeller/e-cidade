<?php

namespace ECidade\Financeiro\Tesouraria\Relatorios\Bordero\Services;

use DBDate;
use ECidade\Financeiro\Tesouraria\Relatorios\Bordero\Repository\BorderoRepository;
use Exception;

/**
 * Class BorderoServices
 * @package ECidade\Financeiro\Tesouraria\Relatorios\Bordero\Services
 */
class BorderoService
{
    const SINTETICO = 0;
    const ANALITICO = 1;

    /**
     * @var DBDate
     */
    private $dataInicial;
    /**
     * @var string
     */
    private $conta;
    /**
     * @var integer
     */
    private $instituicao;

    /**
     * @return DBDate
     */
    public function getDataInicial()
    {
        return $this->dataInicial;
    }

    /**
     * @return DBDate
     */
    public function getDataFinal()
    {
        return $this->dataFinal;
    }

    /**
     * @return int
     */
    public function getTipoRelatorio()
    {
        return $this->tipoRelatorio;
    }

    /**
     * @var DBDate
     */
    private $dataFinal;
    /**
     * @var integer
     */
    private $tipoRelatorio;

    /**
     * BorderoServices constructor.
     * @param DBDate $dataInicial
     * @param DBDate $dataFinal
     * @param $tipoRelatorio
     * @param $instituicao
     * @param string $conta
     */
    public function __construct(DBDate $dataInicial, DBDate $dataFinal, $tipoRelatorio, $instituicao, $conta = '')
    {
        $this->instituicao = $instituicao;
        $this->dataInicial = $dataInicial;
        $this->dataFinal = $dataFinal;
        $this->tipoRelatorio = $tipoRelatorio;
        if (!empty($conta)) {
            $this->conta = $conta;
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDados()
    {
        $borderoRepository = new BorderoRepository();
        $dados = $borderoRepository->getDados($this->dataInicial, $this->dataFinal, $this->instituicao, $this->conta);

        $contas = [];
        foreach ($dados as $dado) {
            $contas[] = (object)[
                "arquivo" => $dado['arquivo'],
                "conta_pagadora" => $dado['conta_pagadora'],
                "data_pagamento" => new DBDate($dado['data_pagamento']),
                "data_geracao" => new DBDate($dado['data_geracao']),
                "movimento" => $dado['movimento'],
                "empenho" => $dado['empenho'],
                "slip" => $dado['slip'],
                "ocorrencia" => $dado['ocorrencia'],
                "credor" => $dado['credor'],
                "valor" => $dado['valor'],
                "sequencial" => $dado['db83_sequencial'],
                "descricao" => $dado['db83_descricao'],
            ];
        }
        $contas = $this->organizaAnalitica($contas);
        if ($this->tipoRelatorio == self::SINTETICO) {
            $contas = $this->consolidaSintetico($contas);
        }

        return $contas;
    }

    /**
     * @param array $movimentacoes
     * @return array
     */
    public function organizaAnalitica(array $movimentacoes)
    {
        $contas = [];
        foreach ($movimentacoes as $movimentacao) {
            $contaPagadora = $movimentacao->sequencial;
            if (!array_key_exists($contaPagadora, $contas)) {
                $contas[$contaPagadora] = [];
            }
            if (!array_key_exists($movimentacao->data_geracao->getDate(), $contas[$contaPagadora])) {
                $contas[$contaPagadora][$movimentacao->data_geracao->getDate()] = (object)[
                    "credores" => [],
                    "descricao" => "{$movimentacao->sequencial} - {$movimentacao->descricao}",
                    "conta_pagadora" => $movimentacao->conta_pagadora,
                    "total_dia" => 0
                ];
            }
            $contas[$contaPagadora][$movimentacao->data_geracao->getDate()]->credores[] = $movimentacao;
            $contas[$contaPagadora][$movimentacao->data_geracao->getDate()]->total_dia += $movimentacao->valor;
        }

        return $contas;
    }

    private function consolidaSintetico(array $contas)
    {
        foreach ($contas as $conta => $dias) {
            foreach ($dias as $dia) {
                $credores = [];
                foreach ($dia->credores as $credor) {
                    $match = false;
                    if (array_key_exists($credor->arquivo, $credores)) {
                        foreach ($credores[$credor->arquivo] as $key => $item) {
                            if ($item->data_pagamento == $credor->data_pagamento) {
                                $item->valor += $credor->valor;
                                $match = true;
                            }
                        }
                    }
                    if (!$match) {
                        $credores[$credor->arquivo][] = (object)[
                            "data_pagamento" => $credor->data_pagamento,
                            "credor" => "Diversos credores",
                            "valor" => $credor->valor
                        ];
                    }
                }
                $dia->credores = $credores;
            }
            $contas[$conta] = $dias;
        }

        return $contas;
    }
}
