<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

final class ParcelaRecibo extends LayoutParcelas
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
            'VENCIMENTOPARCELA' => array(
                'name'           => 'VENCPARC{$nroParcela}'
                ,'description'   => 'VENCIMENTO DA PARCELA{$nroParcela}'
                ,'size'           => 10
            )
            ,'VALORPARCELA' => array(
                'name'           => 'VALPARC{$nroParcela}'
                ,'description'   => 'VALOR DA PARCELA{$nroParcela}'
                ,'size'           => 15
            )
            ,'VALORJUROPARCELA' => array(
                'name'           => 'VALJURPARC{$nroParcela}'
                ,'description'   => 'JUROS POR ATRASO DE 1 MES JA CALCULADOS DA PARCELA{$nroParcela}'
                ,'size'           => 15
            )
            ,'VALORMULTAPARCELA' => array(
                'name'           => 'VALMULPARC{$nroParcela}'
                ,'description'   => 'MULTA POR ATRASO DE 1 MES JA CALCULADOS DA PARCELA{$nroParcela}'
                ,'size'           => 15
            )
            ,'NUMPREPARCELA' => array(
                'name'           => 'NUMPREPARC{$nroParcela}'
                ,'description'   => 'CODIGO DE ARRECADACAO DA PARCELA{$nroParcela}'
                ,'size'           => 11
            )
            ,'CODIGOBARRASPARCELA' => array(
                'name'           => 'BARRASPARC{$nroParcela}'
                ,'description'   => 'CODIGO DE BARRAS DA PARCELA{$nroParcela}'
                ,'size'           => 96
            )
            ,'PARCELA' => array(
                'name'           => 'PARC{$nroParcela}'
                ,'description'   => 'PARCELA{$nroParcela}'
                ,'size'           => 3
            )
        );

        parent::__construct($parcelas);
    }
}
