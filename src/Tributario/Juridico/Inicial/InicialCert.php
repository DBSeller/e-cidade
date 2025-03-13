<?php

namespace ECidade\Tributario\Juridico\Inicial;

class InicialCert
{
    /**
     * @var integer
     */
    private $inicial;

    /**
     * @var integer
     */
    private $certidao;

    /**
     * @return int
     */
    public function getInicial()
    {
        return $this->inicial;
    }

    /**
     * @return int
     */
    public function getCertidao()
    {
        return $this->certidao;
    }

    //SET

    /**
     * @param int
     * @return InicialCert
     */
    public function setInicial($inicial)
    {
        $this->inicial = $inicial;
        return $this;
    }

    /**
     * @param int
     * @return InicialCert
     */
    public function setCertidao($certidao)
    {
        $this->certidao = $certidao;
        return $this;
    }

    /**
     * @param  $state
     * @return Diversos
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('v51_inicial', $state)) {
            $self->setInicial($state['v51_inicial']);
        }

        if (array_key_exists('v51_certidao', $state)) {
            $self->setCertidao($state['v51_certidao']);
        }

        return $self;
    }
}
