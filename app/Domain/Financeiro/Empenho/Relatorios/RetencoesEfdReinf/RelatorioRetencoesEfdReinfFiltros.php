<?php

namespace App\Domain\Financeiro\Empenho\Relatorios\RetencoesEfdReinf;

class RelatorioRetencoesEfdReinfFiltros
{
    public $dataInicial;
    public $dataFinal;
    public $instit;
    public $evento;
    public $filtroAgrupaPor;
    public $credor;
    public $unidade;
    public $orgao;
    public $dados;

    public function setDataInicial($dataInicial)
    {
        $this->dataInicial = $dataInicial;
    }

    public function setDataFinal($dataFinal)
    {
        $this->dataFinal = $dataFinal;
    }

    public function setInstit($instit)
    {
        $this->instit = $instit;
    }

    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    public function setFiltroAgrupaPor($agrupaPor)
    {
        $this->filtroAgrupaPor = $agrupaPor;
    }

    public function setFiltroCredor($credor)
    {
        $this->filtroCredor = $credor;
    }

    public function setFiltroOrgao($orgao)
    {
        $this->filtroOrgao = $orgao;
    }

    public function setFiltroUnidade($unidade)
    {
        $this->filtroUnidade = $unidade;
    }

    public function setFiltroEvento($evento)
    {
        $this->filtroEvento = $evento;
    }
}
