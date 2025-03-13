<?php

namespace ECidade\Patrimonial\Protocolo\Servicos;

use ECidade\Patrimonial\Protocolo\Repositorio\TipoDocumentoProcessoRepository;
use ECidade\Patrimonial\Protocolo\Modelo\TipoDocumentoProcesso;
use Exception;

class TipoDocumentoProcessoService
{
    /**
     * @var TipoDocumentoProcessoRepository
     */
    private $repository;
        
    /**
     * TipoDocumentoProcessoService constructor.
     * @param  TipoDocumentoProcessoRepository $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }
 
    /**
     * @param  stdClass $parametros
     * @return TipoDocumentoProcesso
     */
    public function save($parametros)
    {
        $id = !empty($parametros->id) ? $parametros->id : null;

        if (empty($parametros->descricao)) {
            throw new Exception('Campo Descrição obrigatório.');
        }

        if (empty($parametros->sigla)) {
            throw new Exception('Campo Sigla obrigatório.');
        }
        
        $tipoDocumentoProcesso = new TipoDocumentoProcesso($id);
        $tipoDocumentoProcesso->setDescricao($parametros->descricao);
        $tipoDocumentoProcesso->setSigla(strtoupper($parametros->sigla));

        try {
            $this->repository->save($tipoDocumentoProcesso);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $tipoDocumentoProcesso;
    }
    
    /**
     * @return array TipoDocumentoProcesso
     */
    public function getAll()
    {
        try {
            $tiposDocumentoProcesso = $this->repository->getAll();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $tiposDocumentoProcesso;
    }
    
    /**
     * @param  int $id
     * @return boolean
     */
    public function remove($id)
    {
        try {
            $removed = $this->repository->remove($id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $removed;
    }
}
