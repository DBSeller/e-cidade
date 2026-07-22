<?php

/**
 * Class cl_confirmacaorematricula
 * @property $edu01_sequencial
 * @property $edu01_escola
 * @property $edu01_calendario
 * @property $edu01_turma
 * @property $edu01_aluno
 * @property $edu01_criado_em
 */
class cl_confirmacaorematricula extends DAOBasica
{
    /**
     * cl_confirmacaorematricula constructor.
     */
    public function __construct()
    {
        parent::__construct('escola.confirmacaorematricula');
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->edu01_sequencial;
    }

    /**
     * @param int $edu01_sequencial
     */
    public function setSequencial($edu01_sequencial)
    {
        $this->edu01_sequencial = $edu01_sequencial;
    }

    /**
     * @return int
     */
    public function getEscola()
    {
        return $this->edu01_escola;
    }

    /**
     * @param int $edu01_escola
     */
    public function setEscola($edu01_escola)
    {
        $this->edu01_escola = $edu01_escola;
    }

    /**
     * @return int
     */
    public function getCalendario()
    {
        return $this->edu01_calendario;
    }

    /**
     * @param int $edu01_calendario
     */
    public function setCalendario($edu01_calendario)
    {
        $this->edu01_calendario = $edu01_calendario;
    }

    /**
     * @return int
     */
    public function getTurma()
    {
        return $this->edu01_turma;
    }

    /**
     * @param int $edu01_turma
     */
    public function setTurma($edu01_turma)
    {
        $this->edu01_turma = $edu01_turma;
    }

    /**
     * @return int
     */
    public function getAluno()
    {
        return $this->edu01_aluno;
    }

    /**
     * @param int $edu01_aluno
     */
    public function setAluno($edu01_aluno)
    {
        $this->edu01_aluno = $edu01_aluno;
    }

    /**
     * @return string
     */
    public function getCriadoEm()
    {
        return $this->edu01_criado_em;
    }

    /**
     * @param string $edu01_criado_em
     */
    public function setCriadoEm($edu01_criado_em)
    {
        $this->edu01_criado_em = $edu01_criado_em;
    }
}
