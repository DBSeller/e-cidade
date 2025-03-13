<?php


namespace ECidade\Educacao\Escola\Model;

use ECidade\Educacao\Escola\Registry\HabilidadeDesenvolvidaRegistry;
use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadeReferencialCurricularEstadual;
use ECidade\Educacao\Secretaria\BNCC\Registry\HabilidadeReferencialCurricularEstadualRegistry;

/**
 * Class HabilidadeDesenvolvidaReferncial
 * @package ECidade\Educacao\Escola\Model
 */
class HabilidadeDesenvolvidaReferencial
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var HabilidadeDesenvolvida
     */
    private $habilidadeDesenvolvida;

    /**
     * @var HabilidadeReferencialCurricularEstadual
     */
    private $referencialCurricular;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return HabilidadeDesenvolvida
     */
    public function getHabilidadeDesenvolvida()
    {
        return $this->habilidadeDesenvolvida;
    }

    /**
     * @param HabilidadeDesenvolvida $habilidadeDesenvolvida
     */
    public function setHabilidadeDesenvolvida($habilidadeDesenvolvida)
    {
        $this->habilidadeDesenvolvida = $habilidadeDesenvolvida;
    }

    /**
     * @return HabilidadeReferencialCurricularEstadual
     */
    public function getReferencialCurricular()
    {
        return $this->referencialCurricular;
    }

    /**
     * @param HabilidadeReferencialCurricularEstadual $referencialCurricular
     */
    public function setReferencialCurricular($referencialCurricular)
    {
        $this->referencialCurricular = $referencialCurricular;
    }

    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed169_codigo', $state)) {
            $self->setCodigo($state['ed169_codigo']);
        }
        if (array_key_exists('ed169_diario_classe_bncc_habilidade', $state)) {
            $self->setHabilidadeDesenvolvida(
                HabilidadeDesenvolvidaRegistry::get($state['ed169_diario_classe_bncc_habilidade'])
            );
        }
        if (array_key_exists('ed169_bnccreferencial', $state)) {
            $self->setReferencialCurricular(
                HabilidadeReferencialCurricularEstadualRegistry::get($state['ed169_bnccreferencial'])
            );
        }

        return $self;
    }
}
