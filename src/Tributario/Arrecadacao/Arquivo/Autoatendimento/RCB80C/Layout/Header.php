<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Layout;

use \ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Layout\Header as CabecalhoPadrao;

final class Header extends CabecalhoPadrao
{
    public function __construct()
    {
        parent::__construct();

        $fields = array(
            'DATAPROCESSAMENTO' => array(
                 'name'          => 'DATA_PROCESSAMENTO'
                ,'description'   => 'Data do processamento do(s) arquivo(s).'
                ,'size'          => 8
                ,'position'      => 32
            )
            ,'RESERVADO1' => array(
                 'name'          => 'RESERVADO1'
                ,'description'   => 'Campo reservado.'
                ,'size'          => 13
                ,'default'       => ' '
                ,'position'      => 40
            )
            ,'CODIGOCLIENTEBANCO' => array(
                 'name'          => 'CODIGO_CLIENTE_BANCO'
                ,'description'   => 'Informar mesmo código do cliente do cadastro do Convênio (MCI). Definido pelo Banco.'
                ,'size'          => 9
                ,'position'      => 53
            )
            ,'RESERVADO2' => array(
                 'name'          => 'RESERVADO2'
                ,'description'   => 'Campo reservado para o futuro.'
                ,'size'          => 249
                ,'default'       => ' '
                ,'position'      => 62
            )
            ,'SEQUENCIALREGISTRO' => array(
                 'name'          => 'SEQUENCIAL_REGISTRO'
                ,'description'   => 'Número sequencial do registro dentro do arquivo. Obrigatoriamente igual a 1. Deverá ser o primeiro registro do arquivo.'
                ,'size'          => 9
                ,'default'       => '00001'
                ,'position'      => 311
            )
            ,'RESERVADO3' => array(
                 'name'          => 'RESERVADO3'
                ,'description'   => 'Campo reservado.'
                ,'size'          => 210
                ,'default'       => ' '
                ,'position'      => 320
            )
        );

        $this->fields['TIPOARQUIVO']['description'] = 'Identifica o tipo de processamento efetuado no arquivo Remessa. Se \'RT\', o retorno se refere a validação de arquivo de teste. Se \'RP\', o retorno se refere ao processamento de um arquivo de produção.';
        $this->fields['DATAGERACAO']['description'] = 'Data em que foi gerado o arquivo retorno.';
        $this->fields['IDENTIFICACAOARQUIVO']['description'] = 'Nome que identifica o formato do arquivo retorno para o Convênio.';
        $this->fields['ANOREMESSA']['description'] = 'Ano da remessa a qual o retorno se refere.';

        $this->fields = array_merge($this->fields, $fields);
    }
}
