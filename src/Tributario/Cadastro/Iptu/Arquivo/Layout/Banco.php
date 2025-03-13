<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

final class Banco extends Layout
{
    public function __construct()
    {
        $this->fields = array(
            'TOTALBOMPAGADOR'  => array(
                 'name'        => 'VALTOTALBOMPAGADOR'
                ,'description' => 'VALOR TOTAL DO BOM PAGADOR'
                ,'size'        => 18
            ),
            'AGENCIA'          => array(
                 'name'        => 'AGENCIA'
                ,'description' => 'AGENCIA DO CONVENIO'
                ,'size'        => 5
            ),
            'DIGITOAGENCIA'    => array(
                 'name'        => 'DG_AGENCIA'
                ,'description' => 'DIGITO DA AGENCIA'
                ,'size'        => 1
            ),
            'OPERACAO'         => array(
                 'name'        => 'OPERACAO'
                ,'description' => 'OPERACAO DO CONVENIO'
                ,'size'        => 3
            ),
            'CEDENTE'          => array(
                 'name'        => 'CEDENTE'
                ,'description' => 'CEDENTE DO CONVENIO'
                ,'size'        => 6
            ),
            'DIGITOCEDENTE'    => array(
                 'name'        => 'DG_CEDENTE'
                ,'description' => 'DIGITO DO CEDENTE'
                ,'size'        => 1
            ),
            'CARTEIRA'         => array(
                 'name'        => 'CARTEIRA'
                ,'description' => 'CARTEIRA DO CONVENIO'
                ,'size'        => 6
            ),
            'CONVENIO'         => array(
                 'name'        => 'CONVENIO'
                ,'description' => 'CONVENIO'
                ,'size'        => 4
            ),
            'DATAPROCESSAMENTO'=> array(
                 'name'        => 'DATA_PROCESSAMENTO'
                ,'description' => 'DATA DO PROCESSAMENTO'
                ,'size'        => 10
            ),
            'DESCRICAOCONVENIO'=> array(
                 'name'        => 'DESCRICAO_CONVENIO'
                ,'description' => 'DESCRICAO DO CONVENIO'
                ,'size'        => 50
            ),
        );
    }
}
