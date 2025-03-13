<?php

namespace ECidade\Educacao\Escola\Model;

use Aluno;
use AlunoRepository;
use Calendario;
use CalendarioRepository;
use DateTime;
use ECidade\Educacao\Escola\Repository\ConfirmacaoRematriculaRepository;
use Escola;
use EscolaRepository;
use Exception;
use Turma;
use TurmaRepository;

/**
 * Class ConfirmacaoRematricula
 * @package ECidade\Educacao\Escola\Model
 */
class ConfirmacaoRematricula
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var Escola
     */
    private $escola;
    /**
     * @var Calendario
     */
    private $calendario;
    /**
     * @var Turma
     */
    private $turma;
    /**
     * @var Aluno
     */
    private $aluno;
    /**
     * @var DateTime
     */
    private $criadoEm;

    /**
     * ConfirmacaoRematricula constructor.
     * @param null $sequencial
     * @throws Exception
     */
    public function __construct($sequencial = null)
    {
        if ($sequencial) {
            $confirmacaoRematricula = ConfirmacaoRematriculaRepository::find($sequencial);

            $this->sequencial = $confirmacaoRematricula->getSequencial();
            $this->escola = $confirmacaoRematricula->getEscola();
            $this->calendario = $confirmacaoRematricula->getCalendario();
            $this->turma = $confirmacaoRematricula->getTurma();
            $this->aluno = $confirmacaoRematricula->getAluno();
            $this->criadoEm = $confirmacaoRematricula->getCriadoEm();
        }
    }

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
     * @return Escola
     */
    public function getEscola()
    {
        return $this->escola;
    }

    /**
     * @param Escola $escola
     */
    public function setEscola(Escola $escola)
    {
        $this->escola = $escola;
    }

    /**
     * @return Calendario
     */
    public function getCalendario()
    {
        return $this->calendario;
    }

    /**
     * @param Calendario $calendario
     */
    public function setCalendario(Calendario $calendario)
    {
        $this->calendario = $calendario;
    }

    /**
     * @return Turma
     */
    public function getTurma()
    {
        return $this->turma;
    }

    /**
     * @param Turma $turma
     */
    public function setTurma(Turma $turma)
    {
        $this->turma = $turma;
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
    public function setAluno(Aluno $aluno)
    {
        $this->aluno = $aluno;
    }

    /**
     * @return DateTime
     */
    public function getCriadoEm()
    {
        return $this->criadoEm;
    }

    /**
     * @param DateTime $criadoEm
     */
    public function setCriadoEm(DateTime $criadoEm)
    {
        $this->criadoEm = $criadoEm;
    }

    /**
     * @param array $confirmacao
     * @return ConfirmacaoRematricula
     * @throws Exception
     */
    public static function fromState(array $confirmacao)
    {
        $escola = EscolaRepository::getEscolaByCodigo($confirmacao['edu01_escola']);
        $calendario = CalendarioRepository::getCalendarioByCodigo($confirmacao['edu01_calendario']);
        $turma = TurmaRepository::getTurmaByCodigo($confirmacao['edu01_turma']);
        $aluno = AlunoRepository::getAlunoByCodigo($confirmacao['edu01_aluno']);
        $criadoEm = new DateTime($confirmacao['edu01_criado_em']);

        $confirmacaoRematricula = new self();
        $confirmacaoRematricula->setSequencial($confirmacao['edu01_sequencial']);
        $confirmacaoRematricula->setEscola($escola);
        $confirmacaoRematricula->setCalendario($calendario);
        $confirmacaoRematricula->setTurma($turma);
        $confirmacaoRematricula->setAluno($aluno);
        $confirmacaoRematricula->setCriadoEm($criadoEm);

        return $confirmacaoRematricula;
    }
}
