<?php

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model;

class MatrizSaldoContabil
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var int
     */
    private $mes;
    /**
     * @var int
     */
    private $ano;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
    }

    /**
     * @return int
     */
    public function getMes()
    {
        return (int)$this->mes;
    }

    /**
     * @param int $mes
     */
    public function setMes($mes)
    {
        $this->mes = (int)$mes;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return (int)$this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = (int)$ano;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'mes' => $this->getMes(),
            'ano' => $this->getAno()
        );
    }

    /**
     * @param array $state
     * @return MatrizSaldoContabil
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('c132_sequencial', $state)) {
            $self->setSequencial($state['c132_sequencial']);
        }

        if (array_key_exists('c132_mes', $state)) {
            $self->setMes($state['c132_mes']);
        }

        if (array_key_exists('c132_ano', $state)) {
            $self->setAno($state['c132_ano']);
        }

        return $self;
    }
}
