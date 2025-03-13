<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Layout;

class Header extends Layout
{
    public function __construct()
    {
        parent::__construct();

        $this->fields = array(
            'TIPOREGISTRO' => array(
                 'name'          => 'TIPO_REGISTRO_HEADER'
                ,'description'   => 'Tipo de registro no arquivo. Deve ser único e o primeiro registro do arquivo.'
                ,'size'          => 1
                ,'default'       => 'A'
                ,'position'      => 0
            )
            ,'NUMEROCONVENIO' => array(
                 'name'          => 'NUMERO_CONVENIO'
                ,'description'   => 'Informar o número do Convênio de Recebimentos de Guias. Definido pelo Banco.'
                ,'size'          => 6
                ,'position'      => 1
                ,'default'       => '085673' /* PM Paty de Alferes */
            )
            ,'DATAGERACAO' => array(
                 'name'          => 'DATA_GERACAO'
                ,'description'   => 'Data em que foi gerado o arquivo, menor que data de processamento.'
                ,'size'          => 8
                ,'position'      => 7
            )
            ,'IDENTIFICACAOARQUIVO' => array(
                 'name'          => 'IDENTIFICACAO_ARQUIVO'
                ,'description'   => 'Nome que identifica o formato do arquivo.'
                ,'size'          => 7
                ,'default'       => 'RCB80C'
                ,'position'      => 15
            )
            ,'TIPOARQUIVO' => array(
                 'name'          => 'TIPO_ARQUIVO'
                ,'description'   => 'Se \'T\' (teste) , se \'P\' (produção). Valor fixo \'P\' ou \'T\'.'
                ,'size'          => 2
                ,'default'       => 'P'
                ,'position'      => 22
            )
            ,'PREFIXOAGENCIA' => array(
                 'name'          => 'PREFIXO_AGENCIA'
                ,'description'   => 'Informar código da agência centralizadora do Convênio, sem dígito.'
                ,'size'          => 4
                ,'position'      => 24
            )
            ,'ANOREMESSA' => array(
                 'name'          => 'ANO_REMESSA'
                ,'description'   => 'Informar ano a que se refere a remessa processada pelo Banco. Vide Manual'
                ,'size'          => 4
                ,'position'      => 28
            )
        );
    }
}
