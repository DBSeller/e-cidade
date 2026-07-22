<?php


namespace ECidade\Educacao\Secretaria\BNCC\Model;

/**
 * Class BnccOriginalEducacaoInfantil
 * @package ECidade\Educacao\Secretaria\BNCC\Model
 */
class BnccOriginalEducacaoInfantil
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
     * @var HabilidadeEducacaoInfantil
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
     * @return BnccOriginalEducacaoInfantil
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
     * @return BnccOriginalEducacaoInfantil
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
     * @return BnccOriginalEducacaoInfantil
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
     * @return BnccOriginalEducacaoInfantil
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
     * @return BnccOriginalEducacaoInfantil
     */
    public function setHabilidade($habilidade)
    {
        $this->habilidade = $habilidade;
        return $this;
    }


    /**
     * @param array $state
     * @return BnccOriginalEducacaoInfantil
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed167_sequencial', $state)) {
            $self->setId($state['ed167_sequencial']);
        }
        if (array_key_exists('ed167_disciplina', $state)) {
            $self->setDisciplina($state['ed167_disciplina']);
        }
        if (array_key_exists('ed167_faixa_etaria', $state)) {
            $self->setFaixaEtaria($state['ed167_faixa_etaria']);
        }
        if (array_key_exists('ed167_codigo', $state)) {
            $self->setCodigo($state['ed167_codigo']);
        }
        if (array_key_exists('ed167_habilidade', $state)) {
            $self->setHabilidade($state['ed167_habilidade']);
        }

        return $self;
    }

    public function setHabilidadeComentada(HabilidadeEducacaoInfantil $habilidade)
    {
        $this->habilidadeComentada = $habilidade;
        return $this;
    }

    /**
     * @return HabilidadeEducacaoInfantil
     */
    public function getHabilidadeComentada()
    {
        return $this->habilidadeComentada;
    }
}
