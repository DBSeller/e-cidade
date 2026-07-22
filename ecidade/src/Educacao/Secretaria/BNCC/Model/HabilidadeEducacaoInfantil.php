<?php


namespace ECidade\Educacao\Secretaria\BNCC\Model;

/**
 * Class HabilidadeEducacaoInfantil
 * @package ECidade\Educacao\Secretaria\BNCC\Model
 */
class HabilidadeEducacaoInfantil
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $disciplina;
    /**
     * @var string
     */
    private $faixaEtaria;
    /**
     * @var string
     */
    private $codigo;
    /**
     * @var string
     */
    private $habilidade;
    /**
     * @var int
     */
    private $ano;
    /**
     * @var HabilidadeReferencialCurricularEstadual[]
     */
    private $habilidadesReferencialCurricular;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return HabilidadeEducacaoInfantil
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return HabilidadeEducacaoInfantil
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisciplina()
    {
        return $this->disciplina;
    }

    /**
     * @param string $disciplina
     * @return HabilidadeEducacaoInfantil
     */
    public function setDisciplina($disciplina)
    {
        $this->disciplina = $disciplina;
        return $this;
    }

    /**
     * @return string
     */
    public function getFaixaEtaria()
    {
        return $this->faixaEtaria;
    }

    /**
     * @param string $faixaEtaria
     * @return HabilidadeEducacaoInfantil
     */
    public function setFaixaEtaria($faixaEtaria)
    {
        $this->faixaEtaria = $faixaEtaria;
        return $this;
    }

    /**
     * @return string
     */
    public function getHabilidade()
    {
        return $this->habilidade;
    }

    /**
     * @param string $habilidade
     * @return HabilidadeEducacaoInfantil
     */
    public function setHabilidade($habilidade)
    {
        $this->habilidade = $habilidade;
        return $this;
    }

    /**
     * @return HabilidadeReferencialCurricularEstadual[]
     */
    public function getHabilidadesReferencialCurricular()
    {
        return $this->habilidadesReferencialCurricular;
    }

    /**
     * @param HabilidadeReferencialCurricularEstadual[] $habilidadesReferencialCurricular
     */
    public function setHabilidadesReferencialCurricular(array $habilidadesReferencialCurricular)
    {
        $this->habilidadesReferencialCurricular = $habilidadesReferencialCurricular;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @param array $state
     * @return HabilidadeEducacaoInfantil
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed147_sequencial', $state)) {
            $self->setId($state['ed147_sequencial']);
        }
        if (array_key_exists('ed147_disciplina', $state)) {
            $self->setDisciplina($state['ed147_disciplina']);
        }
        if (array_key_exists('ed147_faixa_etaria', $state)) {
            $self->setFaixaEtaria($state['ed147_faixa_etaria']);
        }
        if (array_key_exists('ed147_codigo', $state)) {
            $self->setCodigo($state['ed147_codigo']);
        }
        if (array_key_exists('ed147_habilidade', $state)) {
            $self->setHabilidade($state['ed147_habilidade']);
        }
        if (array_key_exists('ed147_ano', $state)) {
            $self->setAno($state['ed147_ano']);
        }

        return $self;
    }
}
