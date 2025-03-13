<?php


namespace ECidade\Educacao\Secretaria\BNCC\Model;

use ECidade\Educacao\Escola\Model\ComponenteCurricular;
use ECidade\Educacao\Escola\Registry\ComponenteCurricularRegistry;
use ECidade\Educacao\Secretaria\BNCC\Registry\DisciplinaRegistry;
use Exception;

/**
 * Class DisciplinaEquivalente
 * @package ECidade\Educacao\Secretaria\BNCC\Model
 */
class DisciplinaEquivalente
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var ComponenteCurricular
     */
    private $disciplinaEcidade;

    /**
     * @var Disciplina
     */
    private $disciplinaBncc;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return DisciplinaEquivalente
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return ComponenteCurricular
     */
    public function getDisciplinaEcidade()
    {
        return $this->disciplinaEcidade;
    }

    /**
     * @param ComponenteCurricular $disciplinaEcidade
     * @return DisciplinaEquivalente
     */
    public function setDisciplinaEcidade(ComponenteCurricular $disciplinaEcidade)
    {
        $this->disciplinaEcidade = $disciplinaEcidade;
        return $this;
    }

    /**
     * @return Disciplina
     */
    public function getDisciplinaBncc()
    {
        return $this->disciplinaBncc;
    }

    /**
     * @param Disciplina $disciplinaBncc
     * @return DisciplinaEquivalente
     */
    public function setDisciplinaBncc(Disciplina $disciplinaBncc)
    {
        $this->disciplinaBncc = $disciplinaBncc;
        return $this;
    }

    /**
     * @param array $state
     * @return DisciplinaEquivalente
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed153_sequencial', $state)) {
            $self->setCodigo($state['ed153_sequencial']);
        }
        if (array_key_exists('ed153_caddisciplina', $state)) {
            $self->setDisciplinaEcidade(ComponenteCurricularRegistry::get($state['ed153_caddisciplina']));
        }
        if (array_key_exists('ed153_bnccdisciplina', $state)) {
            $self->setDisciplinaBncc(DisciplinaRegistry::get($state['ed153_bnccdisciplina']));
        }

        return $self;
    }
}
