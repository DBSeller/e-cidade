<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

final class Imovel extends Layout
{
    public function __construct ()
    {
        $this->fields = array(
            'TIPOIMOVELCODIGO' => array(
                'name'         => 'ESPECIE'
                ,'description' => 'CODIGO DO TIPO DO IMOVEL - 1 = TERRITORIAL E 2 = PREDIAL'
                ,'size'        => 1
            )
            ,'TIPOIMOVELDESCRICAO' => array(
                'name'         => 'TIPOIMOVEL'
                ,'description' => 'EXPRESSAO DO TIPO DO IMOVEL - TERRITORIAL OU PREDIAL'
                ,'size'        => 11
            )
            ,'MATRICULA' => array(
                'name'         => 'MATRICULA'
                ,'description' => 'MATRICULA'
                ,'size'        => 10
            )
            ,'EXERCICIO' => array(
                'name'         => 'EXERCICIO'
                ,'description' => 'EXERCÍCIO DO CALCULO'
                ,'size'        => 4
            )
            ,'NOTIFICACAO' => array(
                'name'         => 'NOTIFICACAO'
                ,'description' => 'NOTIFICACAO'
                ,'size'        => 10
            )
            ,'ZONAENTREGA' => array(
                'name'         => 'ZONAENTREGA'
                ,'description' => 'ZONA DE ENTREGA'
                ,'size'        => 5
            )
            ,'ZONAFISCALLOTE' => array(
                'name'         => 'ZONAFISCALLOTE'
                ,'description' => 'ZONA FISCAL DA TABELA LOTE'
                ,'size'        => 5
            )
            ,'SETORFISCAL' => array(
                'name'         => 'SETORFISCAL'
                ,'description' => 'SETOR FISCAL'
                ,'size'        => 5
            )
            ,'SETORCARTOGRAFICA' => array(
                'name'         => 'SETORCARTO'
                ,'description' => 'SETOR CARTOGRAFICO (DO SETOR/QUADRA/LOTE)'
                ,'size'        => 4
            )
            ,'QUADRACARTOGRAFICA' => array(
                'name'         => 'QUADRACARTO'
                ,'description' => 'QUADRA CARTOGRAFICA'
                ,'size'        => 4
            )
            ,'LOTECARTOGRAFICA' => array(
                'name'         => 'LOTECARTO'
                ,'description' => 'LOTE CARTOGRAFICA'
                ,'size'        => 4
            )
            ,'SUBLOTE' => array(
                'name'         => 'SUBLOTELOC'
                ,'description' => 'SUBLOTE'
                ,'size'        => 4
            )
        );
    }
}
