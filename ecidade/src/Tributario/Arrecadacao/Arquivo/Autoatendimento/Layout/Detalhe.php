<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Layout;

class Detalhe extends Layout
{
    public function __construct()
    {
        parent::__construct();

        $this->fields = array(
        'TIPOREGISTRO' => array(
           'name'          => 'TIPO_REGISTRO_DETALHE'
          ,'description'   => 'Contém o código tipo do registro no arquivo. Mínimo de um registro detalhe no arquivo.'
          ,'size'          => 1
          ,'position'      => 0
          ,'default'       => '2'
        )
        ,'RESPONSAVELDEBITO' => array(
           'name'          => 'RESPONSAVEL_DEBITO'
          ,'description'   => 'Nome do órgão responsável pela emissão do débito.'
          ,'size'          => 60
          ,'position'      => 1
        )
        ,'TIPOATUALIZACAO' => array(
           'name'          => 'TIPO_ATUALIZACAO'
          ,'description'   => 'Informar código do tipo de atualização do registro no arquivo (I/A/E).'
          ,'size'          => 2
          ,'position'      => 61
        )
        ,'IDENTIFICACAODEVEDOR' => array(
           'name'          => 'IDENTIFICACAO_DEVEDOR'
          ,'description'   => 'Informar número CPF/CNPJ do Contribuinte na Receita Federal. CPF ou CNPJ válido.'
          ,'size'          => 14
          ,'position'      => 63
        )
        ,'IDENTIFICADORDEBITO' => array(
           'name'          => 'IDENTIFICADOR_DEBITO'
          ,'description'   => 'Identifica para o devedor qual a origem do débito. (Diferente de spaces).'
          ,'size'          => 20
          ,'position'      => 77
        )
        ,'REFERENCIADEBITO' => array(
           'name'          => 'REFERENCIA_DEBITO'
          ,'description'   => 'Informar a qual período se refere o débito (diferente de spaces).'
          ,'size'          => 20
          ,'position'      => 97
        )
        ,'DETALHAMENTO_DEBITO' => array(
           'name'          => 'DETALHAMENTO_DEBITO'
          ,'description'   => 'Identifica para o devedor a que se refere o débito. Comp. do Identificador do débito.'
          ,'size'          => 100
          ,'position'      => 117
        )
        ,'VENCIMENTOCODIGOBARRAS' => array(
           'name'          => 'VENCIMENTO_CODIGO_BARRAS'
          ,'description'   => 'Data válida maior que a data de processamento do arquivo.'
          ,'size'          => 8
          ,'position'      => 217
        )
        ,'CODIGOBARRAS' => array(
           'name'          => 'CODIGO_BARRAS'
          ,'description'   => 'Informar código de barras no padrão Febraban.'
          ,'size'          => 48
          ,'position'      => 225
        )
        ,'VALORDEBITO' => array(
           'name'          => 'VALOR_DEBITO'
          ,'description'   => 'Informar ao devedor o valor efetivo do débito. Deve ser maior que zeros.'
          ,'size'          => 11
          ,'position'      => 273
        )
        ,'TIPODEBITO' => array(
           'name'          => 'TIPO_DEBITO'
          ,'description'   => 'Lista de tipos definida no manual com a tabela arquivoautoatendimentotipo'
          ,'size'          => 2
          ,'position'      => 284
        )
        ,'NUMEROPARCELA' => array(
           'name'          => 'NUMERO_PARCELA'
          ,'description'   => '000- Cota única - Parcelas (001,002,003)... - 999 (Sem parcelamento).'
          ,'size'          => 3
          ,'position'      => 286
        )
        ,'CHASSIVEICULO' => array(
           'name'          => 'CHASSI_VEICULO'
          ,'description'   => 'Informar código do chassi. Opcional'
          ,'size'          => 17
          ,'position'      => 289
        )
        ,'VALORVENALIMOVEL' => array(
           'name'          => 'VALOR_VENAL_IMOVEL'
          ,'description'   => 'Informar valor do imóvel. Opcional'
          ,'size'          => 11
          ,'position'      => 306
        )
        ,'CODIGOBARRASAGRUPADOR' => array(
           'name'          => 'CODIGO_BARRAS_AGRUPADOR'
          ,'description'   => 'NÃO deve ser informado quando número de parcela = 999. Ver Manual.'
          ,'size'          => 48
          ,'position'      => 317
        )
        ,'TIPODEPESSOA' => array(
           'name'          => 'TIPO_DE_PESSOA'
          ,'description'   => 'Informar se o campo IDENTIFICACAO_DEVEDOR se refere a um CPF ou a um CNPJ..'
          ,'size'          => 1
          ,'position'      => 365
        )
        ,'RESERVADO' => array(
           'name'          => 'RESERVADO'
          ,'description'   => 'Campo reservado para o futuro.'
          ,'size'          => 75
          ,'default'       => ' '
          ,'position'      => 366
        )
        ,'SEQUENCIAL' => array(
           'name'          => 'SEQUENCIAL'
          ,'description'   => 'Número sequencial do registro dentro do arquivo.'
          ,'size'          => 9
          ,'default'       => 1
          ,'position'      => 442
        )
        );
    }
}
