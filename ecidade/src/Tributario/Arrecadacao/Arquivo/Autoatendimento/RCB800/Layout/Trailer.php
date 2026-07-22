<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Layout;

use \BusinessException;

final class Trailer extends Layout
{
    public function __construct()
    {
        $this->fields = array(
            'TIPOREGISTRO' => array(
                 'name'          => 'TIPO_REGISTRO_TRAILER'
                ,'description'   => 'Deve ser único no arquivo e ser o último registro.'
                ,'size'          => 1
                ,'default'       => 'Z'
            )
            ,'QUANTIDADEREGISTROS' => array(
                 'name'          => 'QUANTIDADE_REGISTROS'
                ,'description'   => 'Informar quantidade total de registros detalhe do arquivo.'
                ,'size'          => 9 
            )
            ,'RESERVADO' => array(
                 'name'          => 'RESERVADO'
                ,'description'   => 'Campo reservado para o futuro.'
                ,'size'          => 431 
                ,'default'       => ' '
            )
            ,'SEQUENCIAL' => array(
                 'name'          => 'SEQUENCIAL'
                ,'description'   => 'Número sequencial do registro dentro do arquivo. Deverá vir após o bloco de registros detalhe acrescido de 1.'
                ,'size'          => 9 
                ,'default'       => 1
            )
        );
    }
}


