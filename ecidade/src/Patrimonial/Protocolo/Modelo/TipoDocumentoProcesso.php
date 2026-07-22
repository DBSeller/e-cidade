<?php

namespace ECidade\Patrimonial\Protocolo\Modelo;

use ECidade\Patrimonial\Protocolo\Repositorio\TipoDocumentoProcessoRepository;

class TipoDocumentoProcesso
{
    
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var String
     */
    private $descricao;

    /**
     * @var String
     */
    private $sigla;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct($sequencial = null)
    {
        if (!empty($sequencial)) {
            $repository = new TipoDocumentoProcessoRepository(new \cl_prottipodocumentoprocesso);
            $tipoDocumentoProcesso = $repository->find($sequencial);
            
            $this->sequencial = $tipoDocumentoProcesso->getSequencial();
            $this->descricao = $tipoDocumentoProcesso->getDescricao();
            $this->sigla = $tipoDocumentoProcesso->getSigla();
        }
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @return String
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @return String
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @param String $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @param String $sigla
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
    }
    
    /**
     * @param array $state
     * @return TipoDocumentoProcesso
     */
    public static function fromState($state)
    {
        $tipoDocumentoProcesso = new TipoDocumentoProcesso();

        if (array_key_exists('p91_sequencial', $state)) {
            $tipoDocumentoProcesso->setSequencial((int) $state['p91_sequencial']);
        }

        if (array_key_exists('p91_descricao', $state)) {
            $tipoDocumentoProcesso->setDescricao($state['p91_descricao']);
        }

        if (array_key_exists('p91_sigla', $state)) {
            $tipoDocumentoProcesso->setSigla($state['p91_sigla']);
        }
        
        return $tipoDocumentoProcesso;
    }

    
    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'p91_sequencial' => $this->getSequencial(),
            'p91_descricao' => $this->getDescricao(),
            'p91_sigla' => $this->getSigla()
        );
    }
}
