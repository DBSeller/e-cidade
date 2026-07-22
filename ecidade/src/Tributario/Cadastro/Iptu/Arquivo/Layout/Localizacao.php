<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

final class Localizacao extends Layout
{
    public function __construct ()
    {
        $this->fields = array(
            'SEQUENCIALSETORLOCALIZACAO' => array(
                'name'            => 'SEQUENCIALSETORLOCALIZACAO'
                ,'description'     => 'SEQUENCIAL DO SETOR DE LOCALIZACAO'
                ,'size'            => 10
            )
            ,'CODIGOPROPRIOSETORLOCALIZACAO' => array(
                'name'            => 'CODPROPRIOSETORLOCALIZACAO'
                ,'description'     => 'CODIGO PROPRIO DO SETOR DE LOCALIZACAO'
                ,'size'            => 10
            )
            ,'DESCRICAOSETORLOCALIZACAO' => array(
                'name'            => 'DESCRSETORLOCALIZACAO'
                ,'description'     => 'DESCRICAO DO SETOR DE LOCALIZACAO'
                ,'size'            => 40
            )
            ,'QUADRALOCALIZACAO' => array(
                'name'            => 'QUADRALOCALIZACAO'
                ,'description'     => 'QUADRA DE LOCALIZACAO'
                ,'size'            => 10
            )
            ,'LOTELOCALIZACAO' => array(
                'name'            => 'LOTELOCALIZACAO'
                ,'description'     => 'LOTE DE LOCALIZACAO'
                ,'size'            => 10
            )
        );
    }
}
