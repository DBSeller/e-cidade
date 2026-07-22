<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use ECidade\Tributario\Library\Entity;

final class NossoNumero extends Entity
{
    const NOSSO_NUMERO_PARCELA        = 'NOSSONUMEROPARCELA';
    const DIGITO_NOSSO_NUMERO_PARCELA = 'DIGITONOSSONUMEROPARCELA';
    
    /**
     * @var integer
     *
     * NOSSO NUMERO VERSAO 2 PARCELA
     */
    private $nossoNumeroParcela;

    /**
     * @var integer
     *
     * DIGITO DO NOSSO NUMERO VERSAO 2 PARCELA
     */
    private $digitoNossoNumeroParcela;

    
    /**
     * @param integer
     *
     * Define o NOSSO NUMERO VERSAO 2 PARCELA
     */
    public function setNossoNumeroParcela($nossoNumeroParcela)
    {
        $this->nossoNumeroParcela = $nossoNumeroParcela;
        return $this;
    }
    
    /**
     * @param integer
     *
     * Define o DIGITO DO NOSSO NUMERO VERSAO 2 PARCELA
     */
    public function setDigitoNossoNumeroParcela($digitoNossoNumeroParcela)
    {
        $this->digitoNossoNumeroParcela = $digitoNossoNumeroParcela;
        return $this;
    }

    /**
     * @return integer
     *
     * Retorna o NOSSO NUMERO VERSAO 2 PARCELA
     */
    public function getNossoNumeroParcela()
    {
        return $this->nossoNumeroParcela;
    }
    
    /**
     * @return integer
     *
     * Retorna o DIGITO DO NOSSO NUMERO VERSAO 2 PARCELA
     */
    public function getDigitoNossoNumeroParcela()
    {
        return $this->digitoNossoNumeroParcela;
    }
}
