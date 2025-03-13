<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

final class Parcela extends LayoutParcelasReceitas
{
    public function __construct($parcelas, $receitas)
    {
        $this->fields = array(
            'PARCELA' => array(
                'name'         => 'PARC{$nroParcela}{$codigoReceita}'
                ,'description' => 'PARCELA {$nroParcela} - RECEITA {$codigoReceita}'
                ,'size'        => 3
            )
            ,'RECEITA' => array(
                'name'         => 'REC{$nroParcela}{$codigoReceita}'
                ,'description' => 'RECEITA {$nroParcela} - PARCELA {$codigoReceita}'
                ,'size'        => 3
            )
            ,'VALORPARCELARECEITA' => array(
                'name'         => 'VALPARCREC{$nroParcela}{$codigoReceita}'
                ,'description' => 'VALOR DA PARCELA {$nroParcela} - RECEITA {$codigoReceita}'
                ,'size'        => 15
            )
        );
        parent::__construct($parcelas, $receitas);
    }
}
