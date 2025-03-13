<?php

namespace App\Domain\Financeiro\Tesouraria\Relatorios\Tef\ExtratoContaBancaria;

class ExtratoContaBancariaModel
{
    public $dados;

    public $imprime_analitico;

    public $imprime_historico;

    public $data_inicial;

    public $data_final;

    public $somente_contas_bancarias;

    public $agrupapor;

    public $receitaspor;

    public $saldo_dia_debito;

    public $saldo_dia_credito;

    public $saldo_dia_final;

    public $totalizador_diario;
    
    public function setDataInicial($data_inicial)
    {

        $this->data_inicial = $data_inicial;
    }

    public function setDataFinal($data_final)
    {

        $this->data_final = $data_final;
    }

    public function setDados($dados)
    {

        $this->dados = $dados;
    }

    public function setAgrupaPor($agrupapor)
    {

        $this->agrupapor = $agrupapor;
    }

    public function setReceitasPor($receitaspor)
    {

        $this->receitaspor = $receitaspor;
    }

    
    public function setTotalizadorDiario($totalizador_diario)
    {
        $this->totalizador_diario = $totalizador_diario;
    }

    public function setImprimeAnalitico($imprime_analitico)
    {
        $this->imprime_analitico = $imprime_analitico;
    }

    public function setImprimeHistorico($imprime_historico)
    {
        $this->imprime_historico = $imprime_historico;
    }

    public function setSomenteContasBancarias($somente_contas_bancarias)
    {
        $this->somente_contas_bancarias = $somente_contas_bancarias;
    }
}
