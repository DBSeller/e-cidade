<?php


namespace ECidade\Educacao\Escola\Model;

/**
 * Class ModalidadeEnsino
 * @package ECidade\Educacao\Escola\Model
 */
class ModalidadeEnsino
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $abreviatura;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return ModalidadeEnsino
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return ModalidadeEnsino
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbreviatura()
    {
        return $this->abreviatura;
    }

    /**
     * @param string $abreviatura
     * @return ModalidadeEnsino
     */
    public function setAbreviatura($abreviatura)
    {
        $this->abreviatura = $abreviatura;
        return $this;
    }

    /**
     * @param array $state
     * @return ModalidadeEnsino
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed36_i_codigo', $state)) {
            $self->setCodigo($state['ed36_i_codigo']);
        }
        if (array_key_exists('ed36_c_descr', $state)) {
            $self->setNome($state['ed36_c_descr']);
        }
        if (array_key_exists('ed36_c_abrev', $state)) {
            $self->setAbreviatura($state['ed36_c_abrev']);
        }

        return $self;
    }
}
