<?php

namespace ECidade\RecursosHumanos\ESocial\Entity;

use Servidor;
use ServidorRepository;

/**
 * Mapeia a tabela 'avaliacaogruporespostarhpessoalalteracao'
 *
 * @todo Implementar get/set do campo 'eso02_avaliacaogruporesposta'
 * @todo Implementar get/set do campo 'eso02_empregador'
 * @todo Implementar get/set do campo 'eso02_avaliacao'
 */
class AdmissaoTrabalhador
{
    /**
     * sequencial
     *
     * @var int
     */
    private $codigo;

    private $avaliacaogruporesposta;

    /**
     * rhpessoal
     *
     * @var Servidor
     */
    private $servidor;

    private $empregador;

    private $avaliacao;

    /**
     * @return AdmissaoTrabalhador
     */
    public static function fromState(array $state)
    {
        $alteracao = new self();

        if (array_key_exists('eso02_sequencial', $state)) {
            $alteracao->setCodigo($state['eso02_sequencial']);
        }
        if (array_key_exists('eso02_avaliacaogruporesposta', $state)) {
            /** @todo Carregar a relação */
        }
        if (array_key_exists('eso02_rhpessoal', $state)) {
            $servidor = ServidorRepository::getInstanciaByCodigo($state['eso02_rhpessoal']);
            $alteracao->setServidor($servidor);
        }
        if (array_key_exists('eso02_empregador', $state)) {
            /** @todo Carregar a relação */
        }
        if (array_key_exists('eso02_avaliacao', $state)) {
            /** @todo Carregar a relação */
        }

        return $alteracao;
    }

    /**
     * @return  int
     */
    public function getCodigo()
    {
        return (int)$this->codigo;
    }

    /**
     * @param   int  $codigo  sequencial
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return  Servidor
     */
    public function getServidor()
    {
        return $this->servidor;
    }

    /**
     * @param   Servidor  $servidor  rhpessoal
     */
    public function setServidor(Servidor $servidor)
    {
        $this->servidor = $servidor;
    }
}
