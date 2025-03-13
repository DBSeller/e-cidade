<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

final class UnicaTaxa extends Layout
{
    public function get($counter, $taxa, $unica1 = "", $unica2 = "", $unica3 = "")
    {
        $this->fields = array(
            'CODARRETAXA' => array(
                'name' => 'CODARRETAXA',
                'description' => 'CODIGO DE ARRECADACAO DO DEBITO DE TAXA{$taxa}',
                'size' => 11
            ),
            'VLRCALCTAXA' => array(
                'name' => 'VLRCALCTAXA',
                'description' => 'VALOR DO CALCULO DE TAXA{$taxa}',
                'size' => 11
            ),
            'VLRDESCUNICATAXA1' => array(
                'name' => 'VLRDESCUNICA{$unica1}TAXA{$taxa}',
                'description' => 'VALOR DE DESCONTO NA UNICA DE TAXA{$taxa} {$unica1}',
                'size' => 11
            ),
            'ALIQDESCUNICATAXA1' => array(
                'name' => 'ALIQDESCUNICA{$unica1}TAXA{$taxa}',
                'description' => 'ALIQUOTA DE DESCONTO NA UNICA DE TAXA{$taxa} {$unica1}',
                'size' => 3
            ),
            'VLRUNICATAXA1' => array(
                'name' => 'VLRUNICA{$unica1}TAXA{$taxa}',
                'description' => 'VALOR A SER PAGO DE UNICA DE TAXA{$taxa} {$unica1}',
                'size' => 11
            ),
            'VLRDESCUNICATAXA2' => array(
                'name' => 'VLRDESCUNICA{$unica2}TAXA{$taxa}',
                'description' => 'VALOR DE DESCONTO NA UNICA DE TAXA{$taxa} {$unica2}',
                'size' => 11
            ),
            'ALIQDESCUNICATAXA2' => array(
                'name' => 'ALIQDESCUNICA{$unica2}TAXA{$taxa}',
                'description' => 'ALIQUOTA DE DESCONTO NA UNICA DE TAXA{$taxa} {$unica2}',
                'size' => 3
            ),
            'VLRUNICATAXA2' => array(
                'name' => 'VLRUNICA{$unica2}TAXA{$taxa}',
                'description' => 'VALOR A SER PAGO DE UNICA DE TAXA{$taxa} {$unica2}',
                'size' => 11
            ),
            'VLRDESCUNICATAXA3' => array(
                'name' => 'VLRDESCUNICA{$unica3}TAXA{$taxa}',
                'description' => 'VALOR DE DESCONTO NA UNICA DE TAXA{$taxa} {$unica3}',
                'size' => 11
            ),
            'ALIQDESCUNICATAXA3' => array(
                'name' => 'ALIQDESCUNICA{$unica3}TAXA{$taxa}',
                'description' => 'ALIQUOTA DE DESCONTO NA UNICA DE TAXA{$taxa} {$unica3}',
                'size' => 3
            ),
            'VLRUNICATAXA3' => array(
                'name' => 'VLRUNICA{$unica3}TAXA{$taxa}',
                'description' => 'VALOR A SER PAGO DE UNICA DE TAXA {$taxa}{$unica3}',
                'size' => 11
            )
        );
    }
}
