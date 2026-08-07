<?php

namespace App\Domain\Tributario\Arrecadacao\Services;

use App\Domain\Tributario\Arrecadacao\Reports\ComprovanteDesfazimentoTef;
use App\Domain\Tributario\Arrecadacao\Repositories\OperacoesrealizadastefRepository;

class ComprovanteDesfazimentoTefService extends ComprovanteDesfazimentoTef
{
    /**
     * @var integer
     */
    private $numnov;

    /**
     * @var integer
     */
    private $grupo;

    /**
     * @param int $numnov
     * @return ComprovanteDesfazimentoTefService
     */
    public function setNumnov($numnov)
    {
        $this->numnov = $numnov;
        return $this;
    }

    /**
     * @param int $grupo
     * @return ComprovanteDesfazimentoTefService
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;
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
        $aOperacoesDesfeitas = $operacoesrealizadastefRepository->getAllDesfeitasByNumnovGrupo(
            $this->numnov,
            $this->grupo
        );

        $this->setOperacoes($aOperacoesDesfeitas);
    }
}
