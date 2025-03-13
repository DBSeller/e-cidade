<?php
/**
 * Created by PhpStorm.
 * User: andri
 * Date: 29/04/2019
 * Time: 12:19
 */

namespace ECidade\Educacao\Escola\Model;


use DBDate;
use ECidade\Educacao\Escola\Registry\AlunoRegistry;
use Escola;
use EscolaRepository;

class AlunoNecessidadeEspecial
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var Aluno
     */
    private $aluno;
    private $necessidade;
    /**
     * @var boolean
     */
    private $principal;
    /**
     * @var integer
     */
    private $apoio;
    /**
     * @var DBDate
     */
    private $data;
    /**
     * @var integer
     */
    private $tipoDiagnostico;

    /**
     * @var Escola
     */
    private $escola;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return AlunoNecessidadeEspecial
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
     * @return AlunoNecessidadeEspecial
     */
    public function setAluno($aluno)
    {
        $this->aluno = $aluno;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNecessidade()
    {
        return $this->necessidade;
    }

    /**
     * @param mixed $necessidade
     * @return AlunoNecessidadeEspecial
     */
    public function setNecessidade($necessidade)
    {
        $this->necessidade = $necessidade;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrincipal()
    {
        return $this->principal;
    }

    /**
     * @param bool $principal
     * @return AlunoNecessidadeEspecial
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;
        return $this;
    }

    /**
     * @return int
     */
    public function getApoio()
    {
        return $this->apoio;
    }

    /**
     * @param int $apoio
     * @return AlunoNecessidadeEspecial
     */
    public function setApoio($apoio)
    {
        $this->apoio = $apoio;
        return $this;
    }

    /**
     * @return DBDate
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param DBDate $data
     * @return AlunoNecessidadeEspecial
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipoDiagnostico()
    {
        return $this->tipoDiagnostico;
    }

    /**
     * @param int $tipoDiagnostico
     * @return AlunoNecessidadeEspecial
     */
    public function setTipoDiagnostico($tipoDiagnostico)
    {
        $this->tipoDiagnostico = $tipoDiagnostico;
        return $this;
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
     * @return AlunoNecessidadeEspecial
     */
    public function setEscola($escola)
    {
        $this->escola = $escola;
        return $this;
    }

    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed214_i_codigo', $state)) {
            $self->setCodigo($state['ed214_i_codigo']);
        }
        if (array_key_exists('ed214_i_aluno', $state)) {
            $self->setAluno(AlunoRegistry::get($state['ed214_i_aluno']));
        }
        if (array_key_exists('ed214_i_necessidade', $state)) {
            $self->setNecessidade($state['ed214_i_necessidade']);
        }
        if (array_key_exists('ed214_c_principal', $state)) {
            $self->setPrincipal($state['ed214_c_principal'] === 'SIM');
        }
        if (array_key_exists('ed214_i_apoio', $state)) {
            $self->setApoio($state['ed214_i_apoio']);
        }
        if (array_key_exists('ed214_d_data', $state)) {
            $self->setData( !empty($state['ed214_d_data']) ? new DBDate($state['ed214_d_data']) : null);
        }
        if (array_key_exists('ed214_i_tipo', $state)) {
            $self->setTipoDiagnostico($state['ed214_i_tipo']);
        }
        if (array_key_exists('ed214_i_escola', $state)) {
            $self->setEscola(EscolaRepository::getEscolaByCodigo($state['ed214_i_escola']));
        }

        return $self;
    }
}
