<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

final class NossoNumeroVersao2 extends LayoutParcelas
{
    public function __construct ($parcelas)
    {
        $this->fields = array(
            'NOSSONUMEROPARCELAVERSAO2'  => array(
                'name'           => 'NOSSO_NUMERO_VERSAO2_PARC{$nroParcela}'
                ,'description'   => 'NOSSO NUMERO VERSAO 2 PARCELA{$nroParcela}'
                ,'size'          => 17
            )
            ,'DIGITONOSSONUMEROPARCELAVERSAO2'  => array(
                'name'           => 'DG_NOSSO_NUMERO_VERSAO2_PARC{$nroParcela}'
                ,'description'   => 'DIGITO DO NOSSO NUMERO VERSAO 2 PARCELA{$nroParcela}'
                ,'size'          => 1
            )
        );

        parent::__construct($parcelas);
    }
}
