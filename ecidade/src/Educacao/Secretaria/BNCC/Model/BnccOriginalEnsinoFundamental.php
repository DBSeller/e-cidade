<?php


namespace ECidade\Educacao\Secretaria\BNCC\Model;

/**
 * Class BnccOriginalEsinoFundamental
 * @package ECidade\Educacao\Secretaria\BNCC\Model
 */
class BnccOriginalEnsinoFundamental
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
     * @var HabilidadesEnsinoFundamental
     */
    private $habilidadeComentada;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return BnccOriginalEnsinoFundamental
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
     * @return BnccOriginalEnsinoFundamental
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
     * @return BnccOriginalEnsinoFundamental
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
     * @return BnccOriginalEnsinoFundamental
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
     * @return BnccOriginalEnsinoFundamental
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
     * @return BnccOriginalEnsinoFundamental
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
     * @return BnccOriginalEnsinoFundamental
     */
    public function setHabilidade($habilidade)
    {
        $this->habilidade = $habilidade;
        return $this;
    }

    /**
     * @param array $state
     * @return BnccOriginalEnsinoFundamental
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed166_sequencial', $state)) {
            $self->setId($state['ed166_sequencial']);
        }
        if (array_key_exists('ed166_disciplina', $state)) {
            $self->setDisciplina($state['ed166_disciplina']);
        }
        if (array_key_exists('ed166_etapa', $state)) {
            $self->setEtapa($state['ed166_etapa']);
        }
        if (array_key_exists('ed166_codigo', $state)) {
            $self->setCodigo($state['ed166_codigo']);
        }
        if (array_key_exists('ed166_unidade_tematica', $state)) {
            $self->setUnidadeTematica($state['ed166_unidade_tematica']);
        }
        if (array_key_exists('ed166_objeto_conhecimento', $state)) {
            $self->setObjetoConhecimento($state['ed166_objeto_conhecimento']);
        }
        if (array_key_exists('ed166_habilidade', $state)) {
            $self->setHabilidade($state['ed166_habilidade']);
        }

        return $self;
    }

    /**
     * @param HabilidadesEnsinoFundamental $habilidade
     * @return $this
     */
    public function setHabilidadeComentada(HabilidadesEnsinoFundamental $habilidade)
    {
        $this->habilidadeComentada = $habilidade;
        return $this;
    }

    /**
     * @return HabilidadesEnsinoFundamental
     */
    public function getHabilidadeComentada()
    {
        return $this->habilidadeComentada;
    }
}
