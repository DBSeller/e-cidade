<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Layout;

use \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Layout\Header as HeaderPattern;

final class Header extends HeaderPattern
{
    public function __construct()
    {
        parent::__construct();

        $fields = array(
             'NUMEROREMESSA' => array(
                 'name'          => 'NUMERO_REMESSA'
                ,'description'   => 'Iniciar com 1 a cada Ano da Remessa.  Vide Manual'
                ,'size'          => 5
            )
            ,'INICIOVIGENCIA' => array(
                 'name'          => 'INICIO_VIGENCIA'
                ,'description'   => 'Data início para disponibilizar os débitos para pagamento maior que data de envio.'
                ,'size'          => 8
            )
            ,'FIMVIGENCIA' => array(
                 'name'          => 'FIM_VIGENCIA'
                ,'description'   => 'Data final de disponibilização dos débitos para pagamento.'
                ,'size'          => 8
            )
            ,'CODIGOCLIENTEBANCO' => array(
                 'name'          => 'CODIGO_CLIENTE_BANCO'
                ,'description'   => 'Informar mesmo código do cliente do cadastro do Convênio. Definido pelo Banco.'
                ,'size'          => 9
                ,'default'       => '104953679' /* PM Paty de Alferes */
            )
            ,'RESERVADO' => array(
                 'name'          => 'RESERVADO'
                ,'description'   => 'Campo reservado para o futuro.'
                ,'size'          => 379
                ,'default'       => ' '
            )
            ,'SEQUENCIAL' => array(
                 'name'          => 'SEQUENCIAL'
                ,'description'   => 'Número sequencial do registro dentro do arquivo. Obrigatoriamente igual a 1.'
                ,'size'          => 9
                ,'default'       => 1
            )
        );

        $this->fields = array_merge($this->fields, $fields);
    }
}
