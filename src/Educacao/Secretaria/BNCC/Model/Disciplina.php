<?php


namespace ECidade\Educacao\Secretaria\BNCC\Model;

use ECidade\Educacao\Secretaria\BNCC\Registry\DisciplinaRegistry;
use ECidade\Enum\Educacao\BNCC\EnsinoEnum;
use Exception;

/**
 * Class Disciplinas
 * @package ECidade\Educacao\Secretaria\BNCC\Model
 */
class Disciplina
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
    private $sigla;
    /**
     * @var string
     */
    private $area_conhecimento;
    /**
     * @var EnsinoEnum
     */
    private $ensino;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Disciplina
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
     * @return Disciplina
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     * @return Disciplina
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
        return $this;
    }

    /**
     * @return string
     */
    public function getAreaConhecimento()
    {
        return $this->area_conhecimento;
    }

    /**
     * @param string $area_conhecimento
     * @return Disciplina
     */
    public function setAreaConhecimento($area_conhecimento)
    {
        $this->area_conhecimento = $area_conhecimento;
        return $this;
    }

    /**
     * @return EnsinoEnum
     */
    public function getEnsino()
    {
        return $this->ensino;
    }

    /**
     * @param EnsinoEnum $ensino
     * @return Disciplina
     */
    public function setEnsino(EnsinoEnum $ensino)
    {
        $this->ensino = $ensino;
        return $this;
    }

    /**
     * @param array $state
     * @return Disciplina
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed149_sequencial', $state)) {
            $self->setCodigo($state['ed149_sequencial']);
        }
        if (array_key_exists('ed149_nome', $state)) {
            $self->setNome($state['ed149_nome']);
        }
        if (array_key_exists('ed149_sigla', $state)) {
            $self->setSigla($state['ed149_sigla']);
        }
        if (array_key_exists('ed149_area_conhecimento', $state)) {
            $self->setAreaConhecimento($state['ed149_area_conhecimento']);
        }
        if (array_key_exists('ed149_ensino', $state)) {
            $self->setEnsino(new EnsinoEnum($state['ed149_ensino']));
        }

        DisciplinaRegistry::set($self);

        return $self;
    }
}
