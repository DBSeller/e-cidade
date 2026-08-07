<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class NossoNumeroUnica extends Entity
{
    const NOSSO_NUMERO_UNICA        = 'NOSSONUMEROUNICA';
    const DIGITO_NOSSO_NUMERO_UNICA = 'DIGITONOSSONUMEROUNICA';
    
    /**
     * @var integer
     *
     * NOSSO NUMERO VERSAO 2 UNICA
     */
    private $nossoNumeroUnica;

    /**
     * @var integer
     *
     * DIGITO DO NOSSO NUMERO VERSAO 2 UNICA
     */
    private $digitoNossoNumeroUnica;

    
    /**
     * @param integer
     *
     * Define o NOSSO NUMERO VERSAO 2 UNICA
     */
    public function setNossoNumeroUnica($nossoNumeroUnica)
    {
        $this->nossoNumeroUnica = $nossoNumeroUnica;
        return $this;
    }
    
    /**
     * @param integer
     *
     * Define o DIGITO DO NOSSO NUMERO VERSAO 2 UNICA
     */
    public function setDigitoNossoNumeroUnica($digitoNossoNumeroUnica)
    {
        $this->digitoNossoNumeroUnica = $digitoNossoNumeroUnica;
        return $this;
    }

    /**
     * @return integer
     *
     * Retorna o NOSSO NUMERO VERSAO 2 UNICA
     */
    public function getNossoNumeroUnica()
    {
        return $this->nossoNumeroUnica;
    }
    
    /**
     * @return integer
     *
     * Retorna o DIGITO DO NOSSO NUMERO VERSAO 2 UNICA
     */
    public function getDigitoNossoNumeroUnica()
    {
        return $this->digitoNossoNumeroUnica;
    }
}
