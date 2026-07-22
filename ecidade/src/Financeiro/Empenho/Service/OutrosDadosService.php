<?php

namespace ECidade\Financeiro\Empenho\Service;

use ECidade\Financeiro\Empenho\Model\OutrosDados;
use ECidade\Financeiro\Empenho\Repository\OutrosDadosRepository;
use Exception;

class OutrosDadosService
{
    /**
     * @var OutrosDadosRepository
     */
    private $repository;
    /**
     * @var integer
     */
    private $codigoEmpenho;
    /**
     * @var array
     */
    private $outrosDados = [];

    public function __construct()
    {
        $this->repository = new OutrosDadosRepository();
    }

    /**
     * @param $codigoEmpenho
     */
    public function setCodigoEmpenho($codigoEmpenho)
    {
        $this->codigoEmpenho = $codigoEmpenho;
    }

    /**
     * @param string $propriedade
     * @param mixed $valor
     */
    public function adicionarDados($propriedade, $valor)
    {
        $this->outrosDados[$propriedade] = $valor;
    }

    /**
     * @throws Exception
     */
    public function salvar()
    {
        if (empty($this->outrosDados)) {
            return;
        }

        $outrosDados = $this->repository->findEmenho($this->codigoEmpenho);

        if (!$outrosDados instanceof OutrosDados) {
            $outrosDados = new OutrosDados();
            $outrosDados->setEmpenho($this->codigoEmpenho);
        }
        $dados = $outrosDados->getOutrosDados();
        if (is_null($dados)) {
            $dados = new \stdClass();
        }

        foreach ($this->outrosDados as $propriedade => $valor) {
            $dados->$propriedade = $valor;
        }

        $outrosDados->setOutrosDados($dados);
        $this->repository->salvar($outrosDados);
    }
}
