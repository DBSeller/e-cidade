<?php


namespace ECidade\Educacao\Secretaria\BNCC\Model;

use ECidade\Educacao\Secretaria\BNCC\Registry\EtapaRegistry;
use Etapa as EtapaEcidade;
use Exception;

/**
 * Class EtapasEquivalentes
 * @package ECidade\Educacao\Secretaria\BNCC\Model
 */
class EtapasEquivalente
{
    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var Etapa
     */
    private $bnccEtapa;

    /**
     * @var EtapaEcidade
     */
    private $etapaEcidade;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return EtapasEquivalente
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return Etapa
     */
    public function getBnccEtapa()
    {
        return $this->bnccEtapa;
    }

    /**
     * @param Etapa $bnccEtapa
     * @return EtapasEquivalente
     */
    public function setBnccEtapa(Etapa $bnccEtapa)
    {
        $this->bnccEtapa = $bnccEtapa;
        return $this;
    }

    /**
     * @return EtapaEcidade
     */
    public function getEtapaEcidade()
    {
        return $this->etapaEcidade;
    }

    /**
     * @param EtapaEcidade $etapaEcidade
     * @return EtapasEquivalente
     */
    public function setEtapaEcidade(EtapaEcidade $etapaEcidade)
    {
        $this->etapaEcidade = $etapaEcidade;
        return $this;
    }

    /**
     * @param array $state
     * @return EtapasEquivalente
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('ed154_sequencial', $state)) {
            $self->setCodigo($state['ed154_sequencial']);
        }
        if (array_key_exists('ed154_bnccetapa', $state)) {
            $self->setBnccEtapa(EtapaRegistry::get($state['ed154_bnccetapa']));
        }
        if (array_key_exists('ed154_serie', $state)) {
            $self->setEtapaEcidade(\EtapaRepository::getEtapaByCodigo($state['ed154_serie']));
        }

        return $self;
    }
}
