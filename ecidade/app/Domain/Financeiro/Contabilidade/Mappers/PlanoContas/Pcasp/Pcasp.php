<?php

namespace App\Domain\Financeiro\Contabilidade\Mappers\PlanoContas\Pcasp;

use App\Domain\Financeiro\Contabilidade\Contracts\PlanoContasPcaspInterface;
use Exception;

abstract class Pcasp implements PlanoContasPcaspInterface
{
    /**
     * Retorna o index da coluna que possui a conta
     * @return integer
     */
    public function indexColunaConta()
    {
        return $this->colunaConta;
    }

    /**
     * Retorna o index da linha que deve começar a importar os dados
     * @return integer
     */
    public function linhaInicio()
    {
        return $this->linhaDados;
    }

    /**
     * Retorna o index da coluna que contém o status da coluna (Se esta Ativa)
     * @return integer
     */
    public function colunaStatus()
    {
        return $this->colunaStatus;
    }

    /**
     * Se deve importar linha.
     * @param $status
     * @return boolean
     */
    public function importar($status)
    {
        return mb_strtoupper($this->statusImportar) === mb_strtoupper($status);
    }

    /**
     * Retorna os index das colunas que devem ser importadas
     * @return array
     */
    public function colunasImportar()
    {
        return $this->colunasImportar;
    }

    /**
     * Mapa das colunas com as colunas do banco de dados
     * @return array
     */
    public function colunasMapper()
    {
        return $this->colunasMapper;
    }
}
