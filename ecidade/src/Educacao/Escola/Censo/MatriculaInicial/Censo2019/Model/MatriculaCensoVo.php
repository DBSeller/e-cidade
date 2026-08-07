<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 03/05/2019
 * Time: 12:52
 */

namespace ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model;


use ECidade\Educacao\Escola\Censo\MatriculaInicial\Censo2019\Model\TurmaCensoVo;
use ECidade\Educacao\Escola\Model\Aluno;
use ECidade\Educacao\Escola\Registry\AlunoRegistry;

class MatriculaCensoVo
{

    protected $codigoMatricula;
    /**
     * @var Aluno
     */
    protected $aluno;

    /**
     * @var TurmaCensoVo
     */
    protected $turma;

    /**
     * @var integer
     */
    protected $etapaCenso;

    /**
     * @var string
     */
    protected $tiposAtendimento;

    /**
     * @return int
     */
    public function getCodigoMatricula()
    {
        return $this->codigoMatricula;
    }

    /**
     * @param int $codigoMatricula
     */
    public function setCodigoMatricula($codigoMatricula)
    {
        $this->codigoMatricula = $codigoMatricula;
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
     */
    public function setAluno($aluno)
    {
        $this->aluno = $aluno;
    }

    /**
     * @return TurmaCensoVo
     */
    public function getTurma()
    {
        return $this->turma;
    }

    /**
     * @param TurmaCensoVo $turma
     */
    public function setTurma($turma)
    {
        $this->turma = $turma;
    }

    /**
     * @return int
     */
    public function getEtapaCenso()
    {
        return $this->etapaCenso;
    }

    /**
     * @param int $etapaCenso
     */
    public function setEtapaCenso($etapaCenso)
    {
        $this->etapaCenso = $etapaCenso;
    }

    /**
     * @return string
     */
    public function getTiposAtendimento()
    {
        return $this->tiposAtendimento;
    }

    /**
     * @param string $tiposAtendimento
     * @return MatriculaCensoVo
     */
    public function setTiposAtendimento($tiposAtendimento)
    {
        $this->tiposAtendimento = trim($tiposAtendimento);
        return $this;
    }


    /**
     * @param array $state
     * @return MatriculaCensoVo
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('codigo_aluno_escola', $state)) {
            $self->setAluno(AlunoRegistry::get($state['codigo_aluno_escola']));
        }
        if (array_key_exists('etapa', $state)) {
            $self->setEtapaCenso($state['etapa']);
        }
        if (array_key_exists('codigo_matricula', $state)) {
            $self->setCodigoMatricula($state['codigo_matricula']);
        }
        if (array_key_exists('atendimento', $state)) {
            $self->setTiposAtendimento($state['atendimento']);
        }

        return $self;
    }
}
