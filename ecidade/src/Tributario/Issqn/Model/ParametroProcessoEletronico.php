<?php

namespace ECidade\Tributario\Issqn\Model;

use DateTime;
use Exception;

class ParametroProcessoEletronico
{
    /**
     * @var integer
     */
    private $alvaraAutonomo;

    /**
     * @var integer
     */
    private $alvaraEmpresa;

    /**
     * @var integer
     */
    private $alvaraMei;

        /**
     * @var integer
     */
    private $alvaraAutonomoProcessoEletronico;

    /**
     * @var integer
     */
    private $alvaraEmpresaProcessoEletronico;

    /**
     * @var integer
     */
    private $alvaraMeiProcessoEletronico;

    /**
     * @var integer
     */
    private $alvaraBaixoRisco;

    /**
     * @var integer
     */
    private $alvaraMedioRisco;

    /**
     * @var integer
     */
    private $alvaraAltoRisco;


    /**
     * @return int
     */
    public function getAlvaraAutonomo()
    {
        return $this->alvaraAutonomo;
    }

    /**
     * @param int $inscricao
     */
    public function setAlvaraAutonomo($alvaraAutonomo)
    {
        $this->alvaraAutonomo = $alvaraAutonomo;
    }

    /**
     * @return int
     */
    public function getAlvaraEmpresa()
    {
        return $this->alvaraEmpresa;
    }

    /**
     * @param int $alvaraEmpresa
     */
    public function setAlvaraEmpresa($alvaraEmpresa)
    {
        $this->alvaraEmpresa = $alvaraEmpresa;
    }

    /**
     * @return int
     */
    public function getAlvaraMei()
    {
        return $this->alvaraMei;
    }

    /**
     * @param int $alvaraMei
     */
    public function setAlvaraMei($alvaraMei)
    {
        $this->alvaraMei = $alvaraMei;
    }

    /**
     * @return int
     */
    public function getAlvaraAutonomoProcessoEletronico()
    {
        return $this->alvaraAutonomoProcessoEletronico;
    }

    /**
     * @param int $alvaraAutonomoProcessoEletronico
     */
    public function setAlvaraAutonomoProcessoEletronico($alvaraAutonomoProcessoEletronico)
    {
        $this->alvaraAutonomoProcessoEletronico = $alvaraAutonomoProcessoEletronico;
    }

    /**
     * @return int
     */
    public function getAlvaraEmpresaProcessoEletronico()
    {
        return $this->alvaraEmpresaProcessoEletronico;
    }

    /**
     * @param int $alvaraEmpresaProcessoEletronico
     */
    public function setAlvaraEmpresaProcessoEletronico($alvaraEmpresaProcessoEletronico)
    {
        $this->alvaraEmpresaProcessoEletronico = $alvaraEmpresaProcessoEletronico;
    }

    /**
     * @return int
     */
    public function getAlvaraMeiProcessoEletronico()
    {
        return $this->alvaraMeiProcessoEletronico;
    }

    /**
     * @param int $alvaraMeiProcessoEletronico
     */
    public function setAlvaraMeiProcessoEletronico($alvaraMeiProcessoEletronico)
    {
        $this->alvaraMeiProcessoEletronico = $alvaraMeiProcessoEletronico;
    }

    /**
     * @return int
     */
    public function getAlvaraBaixoRisco()
    {
        return $this->alvaraBaixoRisco;
    }

    /**
     * @param int $alvaraBaixoRisco
     */
    public function setAlvaraBaixoRisco($alvaraBaixoRisco)
    {
        $this->alvaraBaixoRisco = $alvaraBaixoRisco;
    }

    /**
     * @return int
     */
    public function getAlvaraMedioRisco()
    {
        return $this->alvaraMedioRisco;
    }

    /**
     * @param int $alvaraMedioRisco
     */
    public function setAlvaraMedioRisco($alvaraMedioRisco)
    {
        $this->alvaraMedioRisco = $alvaraMedioRisco;
    }

    /**
     * @return int
     */
    public function getAlvaraAltoRisco()
    {
        return $this->alvaraAltoRisco;
    }

    /**
     * @param int $alvaraAltoRisco
     */
    public function setAlvaraAltoRisco($alvaraAltoRisco)
    {
        $this->alvaraAltoRisco = $alvaraAltoRisco;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'alvaraAutonomo' => $this->getAlvaraAutonomo(),
            'alvaraEmpresa' => $this->getAlvaraEmpresa(),
            'alvaraMei' => $this->getAlvaraMei(),
            'alvaraBaixoRisco' => $this->getAlvaraBaixoRisco(),
            'alvaraMedioRisco' => $this->getAlvaraMedioRisco(),
            'alvaraAltoRisco' => $this->getAlvaraAltoRisco(),
            'alvaraAutonomoProcessoEletronico' => $this->getAlvaraAutonomoProcessoEletronico(),
            'alvaraEmpresaProcessoEletronico' => $this->getAlvaraEmpresaProcessoEletronico(),
            'alvaraMeiProcessoEletronico' => $this->getAlvaraMeiProcessoEletronico()
        );
    }

    /**
     * @param array $state
     * @return IssCadastroSimples
     * @throws Exception
     */
    public function fromState(array $state)
    {

        if (array_key_exists('q150_alvaraautonomo', $state)) {
            $this->setAlvaraAutonomo($state['q150_alvaraautonomo']);
        }

        if (array_key_exists('q150_alvaraempresa', $state)) {
            $this->setAlvaraEmpresa($state['q150_alvaraempresa']);
        }

        if (array_key_exists('q150_alvaramei', $state)) {
            $this->setAlvaraMei($state['q150_alvaramei']);
        }

        if (array_key_exists('q150_alvaraautonomo_processoeletronico', $state)) {
            $this->setAlvaraAutonomoProcessoEletronico($state['q150_alvaraautonomo_processoeletronico']);
        }

        if (array_key_exists('q150_alvaraempresa_processoeletronico', $state)) {
            $this->setAlvaraEmpresaProcessoEletronico($state['q150_alvaraempresa_processoeletronico']);
        }

        if (array_key_exists('q150_alvaramei_processoeletronico', $state)) {
            $this->setAlvaraMeiProcessoEletronico($state['q150_alvaramei_processoeletronico']);
        }

        if (array_key_exists('q150_alvarabaixorisco', $state)) {
            $this->setAlvaraBaixoRisco($state['q150_alvarabaixorisco']);
        }

        if (array_key_exists('q150_alvaramediorisco', $state)) {
            $this->setAlvaraMedioRisco($state['q150_alvaramediorisco']);
        }

        if (array_key_exists('q150_alvaraaltorisco', $state)) {
            $this->setAlvaraAltoRisco($state['q150_alvaraaltorisco']);
        }

        return $this;
    }
}
