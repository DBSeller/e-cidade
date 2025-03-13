<?php


namespace ECidade\RecursosHumanos\Pessoal\Model;

class AfastamentoSituacao
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var string
     */
    private $descricao;

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
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @param array $state
     * @return AfastamentoSituacao
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('rh166_sequencial', $state)) {
            $self->setSequencial((int) $state['rh166_sequencial']);
        }

        if (array_key_exists('rh166_descricao', $state)) {
            $self->setDescricao($state['rh166_descricao']);
        }

        return $self;
    }
}
