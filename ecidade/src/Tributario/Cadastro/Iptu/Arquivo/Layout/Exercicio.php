<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

final class Exercicio extends Layout
{
    public function __construct()
    {
        $this->fields = array(
            'BRANCOS1' => array(
                'name'            => 'BRANCOS'
                ,'description'    => 'BRANCOS'
                ,'size'           => 3
            )
            ,'BRANCOS2' => array(
                'name'            => 'BRANCOS'
                ,'description'    => 'BRANCOS'
                ,'size'           => 5
            )
            ,'DESCRICAOISENCAO' => array(
                'name'            => 'DESCRISEN'
                ,'description'    => 'DESCRICAO DO TIPO DE ISENCAO'
                ,'size'           => 40
            )
            ,'LANCAMENTOISENCAO' => array(
                'name'            => 'LANCISEN'
                ,'description'    => 'DATA DE LANCAMENTO DA ISENCAO'
                ,'size'           => 10
            )
            ,'TOTALLANCADO' => array(
                'name'            => 'TOTREGLANC'
                ,'description'    => 'TOTAL DOS VALORES LANCADOS (IMPOSTO + TAXAS)'
                ,'size'           => 15
            )
            ,'QUANTIDADELANCADO' => array(
                'name'            => 'QUANTREGLANC'
                ,'description'    => 'QUANTIDADE DE LANCAMENTOS (IMPOSTO + TAXAS)'
                ,'size'           => 3
            )
            ,'TOTALLANCADOTAXAS' => array(
                'name'            => 'TOTREGLANCTAXAS'
                ,'description'    => 'TOTAL DOS VALORES LANCADOS (TAXAS)'
                ,'size'           => 15
            )
            ,'QUANTIDADELANCADOTAXAS' => array(
                'name'            => 'QUANTREGLANCTAXAS'
                ,'description'    => 'QUANTIDADE DE LANCAMENTOS (TAXAS)'
                ,'size'           => 3
            )
            ,'VALORCORRIGIDOIPTU' => array(
                'name'            => 'VALORCORRIGIDOIPTU2018'
                ,'description'    => 'VALOR CORRIGIDO DA IPTU DESTA MATRICULA NO ANO 2018'
                ,'size'           => 15
            )
            ,'VALORJUROSIPTU' => array(
                'name'            => 'VALORJUROSIPTU2018'
                ,'description'    => 'VALOR DOS JUROS DA IPTU DESTA MATRICULA NO ANO 2018'
                ,'size'           => 15
            )
            ,'VALORMULTAIPTU' => array(
                'name'            => 'VALORMULTAIPTU2018'
                ,'description'    => 'VALOR DA MULTA DA IPTU DESTA MATRICULA NO ANO 2018'
                ,'size'           => 15
            )
            ,'VALORDESCONTOIPTU' => array(
                'name'            => 'VALORDESCONTOIPTU2018'
                ,'description'    => 'VALOR DO DESCONTO DA IPTU DESTA MATRICULA NO ANO 2018'
                ,'size'           => 15
            )
            ,'VALORTOTALIPTU' => array(
                'name'            => 'VALORTOTALIPTU2018'
                ,'description'    => 'VALOR TOTAL DA IPTU DESTA MATRICULA NO ANO 2018'
                ,'size'           => 15
            )
            ,'CODIGOFACE' => array(
                'name'            => 'CODIGOFACE'
                ,'description'    => 'CODIGO DA FACE'
                ,'size'           => 10
            )
            ,'VALORM2TERRENOFACE' => array(
                'name'            => 'VALORM2TERRENOFACE'
                ,'description'    => 'VALOR DO M2 DO TERRENO BASEADO NA FACE'
                ,'size'           => 20
            )
            ,'VALORM2CONSTRUCAOFACE' => array(
                'name'            => 'VALORM2CONSTRFACE'
                ,'description'    => 'VALOR DO M2 DAS EDIFICACOES BASEADO NA FACE'
                ,'size'           => 20
            )
            ,'VALORVENALTERRENO' => array(
                'name'            => 'VLRVENALTER'
                ,'description'    => 'VALOR VENAL TERRENO'
                ,'size'           => 15
            )
            ,'VALORVENALEDIFICACAO' => array(
                'name'            => 'VLRVENALEDI'
                ,'description'    => 'VALOR VENAL EDIFICACOES'
                ,'size'           => 15
            )
            ,'VALORVENALTOTAL' => array(
                'name'            => 'VLRVENALTOTAL'
                ,'description'    => 'VALOR VENAL TOTAL (TERRENO + EDIFICACOES)'
                ,'size'           => 15
            )
            ,'ALIQUOTA' => array(
                'name'            => 'ALIQ'
                ,'description'    => 'ALIQUOTA'
                ,'size'           => 6
            )
        );
    }
}
