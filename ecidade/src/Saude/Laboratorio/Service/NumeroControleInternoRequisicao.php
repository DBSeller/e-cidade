<?php

namespace ECidade\Saude\Laboratorio\Service;

use ECidade\Saude\Laboratorio\Repository\NumeroControleInternoRequisicao as NumeroControleInternoRequisicaoRepository;
use ECidade\Saude\Laboratorio\Model\NumeroControleInternoRequisicao as NumeroControleInternoRequisicaoModel;
use RequisicaoExame;
use Exception;

/**
 * NumeroControleInternoRequisicao
 */
class NumeroControleInternoRequisicao
{
    
    /**
     * repository
     *
     * @var NumeroControleInternoRequisicaoRepository
     */
    private $repository;
    
    /**
     * __construct
     *
     * @param  mixed $NumeroControleInternoRequisicaoRepository
     * @return void
     */
    public function __construct(NumeroControleInternoRequisicaoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * buscaNumeroControleInternoRequisicao
     *
     * @param $numeroControleInterno
     * @param $ano
     * @return NumeroControleInternoRequisicaoModel
     * @throws \Exception
     */
    public function getNumeroControleInterno($numeroControleInterno, $ano)
    {
        $this->repository->scopeNumeroControleInterno($numeroControleInterno);
        $this->repository->scopeAno($ano);

        return $this->repository->get();
    }
    
    /**
     * getNumeroControleInternoRequisicaoByRequisicao
     *
     * @param $codigoRequisicao
     * @return NumeroControleInternoRequisicaoModel
     * @throws \Exception
     */
    public function getNumeroControleInternoByRequisicao($codigoRequisicao)
    {
        $this->repository->scopeRequisicao($codigoRequisicao);
        return $this->repository->get();
    }
    
    /**
     * salvar
     *
     * @param $parametros
     * @return NumeroControleInternoRequisicaoModel
     * @throws \Exception
     */
    public function salvar($parametros)
    {
        if (empty($parametros->numero)) {
            throw new Exception('Número de Controle Interno da Requisição não informado.');
        }

        if (empty($parametros->ano)) {
            throw new Exception('Ano do Número de Controle Interno da Requisição não informado.');
        }

        if (empty($parametros->codigoRequisicao)) {
            throw new Exception('Código da Requisição não informado.');
        }

        $numeroControleInternoRequisicao = new NumeroControleInternoRequisicaoModel();

        $numeroControleInternoRequisicao->setNumero($parametros->numero);
        $numeroControleInternoRequisicao->setAno($parametros->ano);
        $numeroControleInternoRequisicao->setCodigoRequisicao($parametros->codigoRequisicao);
        
        return $this->repository->salvar($numeroControleInternoRequisicao);
    }

    /**
     * @param \stdClass $parametros
     * @return NumeroControleInternoRequisicaoModel|null
     * @throws \Exception
     */
    public function getNumeroControleInternoByParametros(\stdClass $parametros)
    {
        if (!empty($parametros->numeroControleInterno) && !empty($parametros->ano)) {
            return $this->getNumeroControleInterno($parametros->numeroControleInterno, $parametros->ano);
        }

        if (!empty($parametros->requisicao)) {
            return $this->getNumeroControleInternoByRequisicao($parametros->requisicao);
        }

        return null;
    }
}
