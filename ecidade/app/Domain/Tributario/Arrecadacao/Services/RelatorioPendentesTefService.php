<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use App\Domain\Tributario\Arrecadacao\Reports\RelatorioPendentesTef;
use App\Domain\Tributario\Arrecadacao\Repositories\OperacoesrealizadastefRepository;

class RelatorioPendentesTefService extends RelatorioPendentesTef
{
    /**
     * @var string
     */
    private $dataInicio;

    /**
     * @var string
     */
    private $dataFim;

    /**
     * @var integer
     */
    private $terminal;

    /**
     * @param string $dataInicio
     * @return RelatorioPendentesTefService
     */
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
        return $this;
    }

    /**
     * @param string $dataFim
     * @return RelatorioPendentesTefService
     */
    public function setDataFim($dataFim)
    {
        $this->dataFim = $dataFim;
        return $this;
    }

    /**
     * @param int $terminal
     * @return RelatorioPendentesTefService
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;
        return $this;
    }

    public function gerar()
    {
        $this->buscarDados();
        parent::gerar();
    }

    private function buscarDados()
    {
        $operacoesrealizadastefRepository = new OperacoesrealizadastefRepository();
        $aOperacoes = $operacoesrealizadastefRepository->getAllPendentes(
            $this->dataInicio,
            $this->dataFim,
            $this->terminal
        );

        $this->setOperacoes($aOperacoes);
    }
}
