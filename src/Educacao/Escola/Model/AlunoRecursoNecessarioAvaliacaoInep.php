<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 29/04/2019
 * Time: 15:18
 */

namespace ECidade\Educacao\Escola\Model;


use ECidade\Educacao\Escola\Registry\AlunoRegistry;

class AlunoRecursoNecessarioAvaliacaoInep
{
    private $codigo;
    /**
     * @var Aluno
     */
    private $aluno;
    private $codigoRecursosAvaliacaoInep;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     * @return AlunoRecursoNecessarioAvaliacaoInep
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return Aluno
     */
    public function getAluno()
    {
        return $this->aluno;
    }

    /**
     * @param Aluno $aluno
     * @return AlunoRecursoNecessarioAvaliacaoInep
     */
    public function setAluno($aluno)
    {
        $this->aluno = $aluno;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCodigoRecursosAvaliacaoInep()
    {
        return $this->codigoRecursosAvaliacaoInep;
    }

    /**
     * @param mixed $codigoRecursosAvaliacaoInep
     * @return AlunoRecursoNecessarioAvaliacaoInep
     */
    public function setCodigoRecursosAvaliacaoInep($codigoRecursosAvaliacaoInep)
    {
        $this->codigoRecursosAvaliacaoInep = $codigoRecursosAvaliacaoInep;
        return $this;
    }

    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('ed327_sequencial', $state)) {
            $self->setCodigo($state['ed327_sequencial']);
        }
        if (array_key_exists('ed327_aluno', $state)) {
            $self->setAluno(AlunoRegistry::get($state['ed327_aluno']));
        }
        if (array_key_exists('ed327_recursosavaliacaoinep', $state)) {
            $self->setCodigoRecursosAvaliacaoInep($state['ed327_recursosavaliacaoinep']);
        }

        return $self;
    }
}
