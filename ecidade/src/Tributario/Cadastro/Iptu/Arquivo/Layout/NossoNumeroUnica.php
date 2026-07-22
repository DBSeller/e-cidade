<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

final class NossoNumeroUnica extends LayoutParcelas
{
    public function __construct ($parcelas)
    {
        $this->fields = array(
            'NOSSONUMEROUNICA'  => array(
                'name'           => 'NOSSO_NUMERO_UNICA{$nroParcela}'
                ,'description'   => 'NOSSO NUMERO UNICA {$nroParcela}'
                ,'size'          => 10
            )
            ,'DIGITONOSSONUMEROUNICA'  => array(
                'name'           => 'DG_NOSSO_NUMERO_UNICA{$nroParcela}'
                ,'description'   => 'DIGITO DO NOSSO NUMERO UNICA {$nroParcela}'
                ,'size'          => 1
            )
        );

        parent::__construct($parcelas);
    }
}
