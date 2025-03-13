<?php

namespace ECidade\Educacao\Secretaria\Models;

use ECidade\Enum\Educacao\BNCC\TipoBaseCurricularEnum;
use Exception;

/**
 * @todo só implementamos o parâmetro que iriamos usar no momento
 *
 *
 * Class ParametrosGlobais
 * @package ECidade\Educacao\Secretaria\Models
 */
class ParametrosGlobais
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var TipoBaseCurricularEnum
     */
    private $tipoBaseCurricular;

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
     * @return TipoBaseCurricularEnum
     */
    public function getTipoBaseCurricular()
    {
        return $this->tipoBaseCurricular;
    }

    /**
     * @param TipoBaseCurricularEnum $tipoBaseCurricular
     */
    public function setTipoBaseCurricular(TipoBaseCurricularEnum $tipoBaseCurricular)
    {
        $this->tipoBaseCurricular = $tipoBaseCurricular;
    }

    /**
     * @param array $state
     * @return ParametrosGlobais
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (!empty($state['ed290_sequencial'])) {
            $self->setCodigo($state['ed290_sequencial']);
        }

        if (!empty($state['ed290_bncc'])) {
            $self->setTipoBaseCurricular(new TipoBaseCurricularEnum((int) $state['ed290_bncc']));
        }


        return $self;
    }

    /**
     * @return bool
     */
    public function isReferencialCurricularEstadual()
    {
        if ($this->getTipoBaseCurricular()->value() === TipoBaseCurricularEnum::REFERENCIAL_CURRICULAR_ESTADUAL) {
            return true;
        }

        return false;
    }
}
