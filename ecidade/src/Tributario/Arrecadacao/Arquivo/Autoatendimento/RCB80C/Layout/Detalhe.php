<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Layout;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Layout\Detalhe as DetalhePadrao;

final class Detalhe extends DetalhePadrao
{
    public function __construct()
    {
        parent::__construct();

        $fields = array(
            'RETORNOREGISTRO' => array(
                 'name'          => 'RETORNO_REGISTRO'
                ,'description'   => 'Pode conter um dos erros: 48 - Solicitação de inclusão de barra preexistente 49 - Solicitação de alteração de barra inexistente 50 - Solicitação de exclusão de barra inexistente 52 - Solicitação duplicada de inclusão de barra. 53 - Código de barras informado no Campo Código do Grupo da Guia de Débito não cadastrado.'
                ,'size'          => 2
                ,'default'       => '00'
                ,'position'      => 450
            )
            ,''
        );

        $this->fields = array_merge($this->fields, $fields);
    }
}
