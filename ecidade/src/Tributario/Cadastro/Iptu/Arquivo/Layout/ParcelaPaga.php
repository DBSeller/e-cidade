<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

final class ParcelaPaga extends LayoutParcelas
{
    /**
     * Construtor de classe
     */ 
    public function __construct ($parcelas)
    {
        if(empty($parcelas)) {
            throw BusinessException('Informe o número de parcelas para o layout');
        }
        
        $this->fields = array(
            'DATAPAGAMENTOPARCELA'  => array(
                'name'           => 'DTPGTOPARC{$nroParcela}'
                ,'description'   => 'DATA DO PAGAMENTO DA PARCELA{$nroParcela}'
                ,'size'          => 10
            )
            ,'VALORPAGAMENTOPARCELA'  => array(
                'name'           => 'VALORPGTOPARC{$nroParcela}'
                ,'description'   => 'VALOR DO PAGAMENTO DA PARCELA{$nroParcela}'
                ,'size'          => 15
            )
        );

        parent::__construct($parcelas);
    }
}
