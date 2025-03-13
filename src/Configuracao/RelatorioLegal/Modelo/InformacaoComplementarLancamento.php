<?php

namespace ECidade\Configuracao\RelatorioLegal\Modelo;

use Exception;

class InformacaoComplementarLancamento
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var bool
     */
    private $exclusao = false;

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
     * @param array $state
     * @return InformacaoComplementarLancamento
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('o102_sequencial', $state)) {
            $self->setSequencial($state['o102_sequencial']);
        }

        if (array_key_exists('o102_exclusao', $state)) {
            $self->setExclusao($state['o102_exclusao'] === 't');
        }

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'exclusao' => $this->isExclusao()
        );
    }

    /**
     * @return bool
     */
    public function isExclusao()
    {
        return (bool)$this->exclusao;
    }

    /**
     * @param bool $exclusao
     */
    public function setExclusao($exclusao)
    {
        $this->exclusao = (bool)$exclusao;
    }
}
