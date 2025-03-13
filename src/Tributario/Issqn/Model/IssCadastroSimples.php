<?php

namespace ECidade\Tributario\Issqn\Model;

use DateTime;
use Exception;

class IssCadastroSimples
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $inscricao;

    /**
     * @var DateTime
     */
    private $dataInicial;

    /**
     * @var integer
     */
    private $categoria;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return int
     */
    public function getInscricao()
    {
        return $this->inscricao;
    }

    /**
     * @param int $inscricao
     */
    public function setInscricao($inscricao)
    {
        $this->inscricao = $inscricao;
    }

    /**
     * @return DateTime
     */
    public function getDataInicial()
    {
        return $this->dataInicial;
    }

    /**
     * @param DateTime $dataInicial
     */
    public function setDataInicial($dataInicial)
    {
        $this->dataInicial = $dataInicial;
    }

    /**
     * @return int
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param int $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'inscricao' => $this->getInscricao(),
            'data_inicial' => $this->getDataInicial(),
            'categoria' => $this->getCategoria()
        );
    }

    /**
     * @param array $state
     * @return IssCadastroSimples
     * @throws Exception
     */
    public function fromState(array $state)
    {
        $self = new self();

        if (in_array('q38_sequencial', $state)) {
            $this->setSequencial($state['q38_sequencial']);
        }
        if (in_array('q38_inscr', $state)) {
            $this->setInscricao($state['q38_inscr']);
        }
        if (in_array('q38_dtinicial', $state)) {
            $dataInicial = new DateTime($state['q38_dtinicial']);
            $this->setDataInicial($dataInicial);
        }
        if (in_array('q38_categoria', $state)) {
            $this->setCategoria($state['q38_categoria']);
        }

        return $self;
    }
}
