<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

final class UnicaIptu extends Layout
{
    public function get($counter, $unica1 = "", $unica2 = "", $unica3 = "")
    {
        $this->fields = array(
            'CODARREIPTU' => array(
                'name' => 'CODARREIPTU',
                'description' => 'CODIGO DE ARRECADACAO DO DEBITO DE IPTU',
                'size' => 11
            ),
            'VLRCALCIPTU' => array(
                'name' => 'VLRCALCIPTU',
                'description' => 'VALOR DO CALCULO DE IPTU',
                'size' => 11
            ),
            'VLRDESCUNICAIPTU1' => array(
                'name' => 'VLRDESCUNICA{$unica1}IPTU',
                'description' => 'VALOR DE DESCONTO NA UNICA DE IPTU {$unica1}',
                'size' => 11
            ),
            'ALIQDESCUNICAIPTU1' => array(
                'name' => 'ALIQDESCUNICA{$unica1}IPTU',
                'description' => 'ALIQUOTA DE DESCONTO NA UNICA DE IPTU {$unica1}',
                'size' => 3
            ),
            'VLRUNICAIPTU1' => array(
                'name' => 'VLRUNICA{$unica1}IPTU',
                'description' => 'VALOR A SER PAGO DE UNICA DE IPTU {$unica1}',
                'size' => 11
            ),
            'VLRDESCUNICAIPTU2' => array(
                'name' => 'VLRDESCUNICA{$unica2}IPTU',
                'description' => 'VALOR DE DESCONTO NA UNICA DE IPTU {$unica2}',
                'size' => 11
            ),
            'ALIQDESCUNICAIPTU2' => array(
                'name' => 'ALIQDESCUNICA{$unica2}IPTU',
                'description' => 'ALIQUOTA DE DESCONTO NA UNICA DE IPTU {$unica2}',
                'size' => 3
            ),
            'VLRUNICAIPTU2' => array(
                'name' => 'VLRUNICA{$unica2}IPTU',
                'description' => 'VALOR A SER PAGO DE UNICA DE IPTU {$unica2}',
                'size' => 11
            ),
            'VLRDESCUNICAIPTU3' => array(
                'name' => 'VLRDESCUNICA{$unica3}IPTU',
                'description' => 'VALOR DE DESCONTO NA UNICA DE IPTU {$unica3}',
                'size' => 11
            ),
            'ALIQDESCUNICAIPTU3' => array(
                'name' => 'ALIQDESCUNICA{$unica3}IPTU',
                'description' => 'ALIQUOTA DE DESCONTO NA UNICA DE IPTU {$unica3}',
                'size' => 3
            ),
            'VLRUNICAIPTU3' => array(
                'name' => 'VLRUNICA{$unica3}IPTU',
                'description' => 'VALOR A SER PAGO DE UNICA DE IPTU {$unica3}',
                'size' => 11
            )
        );
    }
}
