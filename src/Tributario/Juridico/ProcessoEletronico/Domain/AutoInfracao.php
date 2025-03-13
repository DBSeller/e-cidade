<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Domain;

/**
 * Class AutoInfracao
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Domain
 */
class AutoInfracao
{

    /**
     * @var $codigo_auto
     */
    private $codigo_auto;

    /**
     * @var $data_auto
     */
    private $data_auto;

    /**
     * @return mixed
     */
    public function getCodigoAuto()
    {
        return $this->codigo_auto;
    }

    /**
     * @param mixed $codigo_auto
     */
    public function setCodigoAuto($codigo_auto)
    {
        $this->codigo_auto = $codigo_auto;
    }

    /**
     * @return mixed
     */
    public function getDataAuto()
    {
        return $this->data_auto;
    }

    /**
     * @param mixed $data_auto
     */
    public function setDataAuto($data_auto)
    {
        $this->data_auto = $data_auto;
    }



}