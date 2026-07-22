<?php


namespace ECidade\Educacao\Escola\Model;

use ECidade\Educacao\Escola\Registry\ConteudoDesenvolvidoRegistry;
use ECidade\Educacao\Secretaria\BNCC\Model\Disciplina;
use ECidade\Educacao\Secretaria\BNCC\Registry\DisciplinaRegistry;
use Exception;

/**
 * Class HabilidadeDesenvolvida
 * @package ECidade\Educacao\Escola\Model
 */
class HabilidadeDesenvolvida
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var ConteudoDesenvolvido
     */
    private $conteudoDesenvolvido;

    /**
     * @var Disciplina
     */
    private $disciplina;

    /**
     * @var string
     */
    private $codigoHabilidade;

    /**
     * @var HabilidadeDesenvolvidaReferencial[]
     */
    private $habilidadesDesenvolvidasReferencial = [];

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return HabilidadeDesenvolvida
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return ConteudoDesenvolvido
     */
    public function getConteudoDesenvolvido()
    {
        return $this->conteudoDesenvolvido;
    }

    /**
     * @param ConteudoDesenvolvido $conteudoDesenvolvido
     * @return HabilidadeDesenvolvida
     */
    public function setConteudoDesenvolvido($conteudoDesenvolvido)
    {
        $this->conteudoDesenvolvido = $conteudoDesenvolvido;
        return $this;
    }

    /**
     * @return Disciplina
     */
    public function getDisciplina()
    {
        return $this->disciplina;
    }

    /**
     * @param Disciplina $disciplina
     * @return HabilidadeDesenvolvida
     */
    public function setDisciplina($disciplina)
    {
        $this->disciplina = $disciplina;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigoHabilidade()
    {
        return $this->codigoHabilidade;
    }

    /**
     * @param string $codigoHabilidade
     * @return HabilidadeDesenvolvida
     */
    public function setCodigoHabilidade($codigoHabilidade)
    {
        $this->codigoHabilidade = $codigoHabilidade;
        return $this;
    }

    /**
     * @param array $state
     * @return HabilidadeDesenvolvida
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed156_codigo', $state)) {
            $self->setCodigo($state['ed156_codigo']);
        }
        if (array_key_exists('ed156_diario_classe_bncc', $state)) {
            $self->setConteudoDesenvolvido(ConteudoDesenvolvidoRegistry::get($state['ed156_diario_classe_bncc']));
        }
        if (array_key_exists('ed156_bnccdisciplinas', $state)) {
            $self->setDisciplina(DisciplinaRegistry::get($state['ed156_bnccdisciplinas']));
        }
        if (array_key_exists('ed156_habilidade', $state)) {
            $self->setCodigoHabilidade($state['ed156_habilidade']);
        }

        return $self;
    }

    /**
     * @param HabilidadeDesenvolvidaReferencial $habilidadeDesenvolvidaReferencia
     */
    public function addHabilidadeReferencial(HabilidadeDesenvolvidaReferencial $habilidadeDesenvolvidaReferencia)
    {
        $this->habilidadesDesenvolvidasReferencial[] = $habilidadeDesenvolvidaReferencia;
    }

    /**
     * @return HabilidadeDesenvolvidaReferencial[]
     */
    public function getHabilidadesReferencial()
    {
        return $this->habilidadesDesenvolvidasReferencial;
    }
}
