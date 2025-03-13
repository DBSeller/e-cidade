<?php


namespace ECidade\Educacao\Secretaria\BNCC\Model;

/**
 * Class HabilidadesEnsinoFundamental
 * @package ECidade\Educacao\Secretaria\BNCC\Model
 */
class HabilidadesEnsinoFundamental
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
    private $etapa;
    /**
     * @var string
     */
    private $codigo;
    /**
     * @var string
     */
    private $unidadeTematica;
    /**
     * @var string
     */
    private $objetoConhecimento;
    /**
     * @var string
     */
    private $habilidade;
    /**
     * @var integer
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
     * @return HabilidadesEnsinoFundamental
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return HabilidadesEnsinoFundamental
     */
    public function setDisciplina($disciplina)
    {
        $this->disciplina = $disciplina;
        return $this;
    }

    /**
     * @return string
     */
    public function getEtapa()
    {
        return $this->etapa;
    }

    /**
     * @param string $etapa
     * @return HabilidadesEnsinoFundamental
     */
    public function setEtapa($etapa)
    {
        $this->etapa = $etapa;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     * @return HabilidadesEnsinoFundamental
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnidadeTematica()
    {
        return $this->unidadeTematica;
    }

    /**
     * @param string $unidadeTematica
     * @return HabilidadesEnsinoFundamental
     */
    public function setUnidadeTematica($unidadeTematica)
    {
        $this->unidadeTematica = $unidadeTematica;
        return $this;
    }

    /**
     * @return string
     */
    public function getObjetoConhecimento()
    {
        return $this->objetoConhecimento;
    }

    /**
     * @param string $objetoConhecimento
     * @return HabilidadesEnsinoFundamental
     */
    public function setObjetoConhecimento($objetoConhecimento)
    {
        $this->objetoConhecimento = $objetoConhecimento;
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
     * @return HabilidadesEnsinoFundamental
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
    public function setHabilidadesReferencialCurricular($habilidadesReferencialCurricular)
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
     * @return HabilidadesEnsinoFundamental
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    /**
     * @param array $state
     * @return HabilidadesEnsinoFundamental
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed148_sequencial', $state)) {
            $self->setId($state['ed148_sequencial']);
        }
        if (array_key_exists('ed148_disciplina', $state)) {
            $self->setDisciplina($state['ed148_disciplina']);
        }
        if (array_key_exists('ed148_etapa', $state)) {
            $self->setEtapa($state['ed148_etapa']);
        }
        if (array_key_exists('ed148_codigo', $state)) {
            $self->setCodigo($state['ed148_codigo']);
        }
        if (array_key_exists('ed148_unidade_tematica', $state)) {
            $self->setUnidadeTematica($state['ed148_unidade_tematica']);
        }
        if (array_key_exists('ed148_objeto_conhecimento', $state)) {
            $self->setObjetoConhecimento($state['ed148_objeto_conhecimento']);
        }
        if (array_key_exists('ed148_habilidade', $state)) {
            $self->setHabilidade($state['ed148_habilidade']);
        }
        if (array_key_exists('ed148_ano', $state)) {
            $self->setAno($state['ed148_ano']);
        }
        return $self;
    }
}
