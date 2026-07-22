<?php

use Classes\PostgresMigration;

class M13822ConsistenciaDocumento1009 extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

delete from consistenciasistema where db160_sequencial = 1000001;

insert into consistenciasistema
     values (1000001,
             100,
             '{
                "tipo": 100,
                "uid": "5dd80c4d3f739",
                "nome": "Encerramento - Doc. 1009",
                "descricao": "Contas com problema de valor invertido para o documento 1009.",
                "formulario": {
                  "campos": [
                      {
                        "propriedade": "conta",
                        "nome": "Reduzido",
                        "chave_primaria": true
                      },
                      {
                        "propriedade": "estrutural",
                        "nome": "Estrutural"
                      },
                      {
                        "propriedade": "descricao",
                        "nome": "Descrição"
                      },
                      {
                        "propriedade": "valor",
                        "nome": "Valor"
                      },
                      {
                        "propriedade": "mensagem",
                        "nome": "Mensagem"
                      }
                    ]
                  },
                  "sql": {
                    "consistencia": "
                       select reduzido_debito as conta,
                              c60_estrut as estrutural,
                              c60_descr as descricao,
                              valor,
                              mensagem
                         from fc_doc_encerramento_2019(
                                 (select fc_getsession(\'db_anousu\')::int),
                                 (select fc_getsession(\'db_instit\')::int)
                              ) as encerramento
                              join conplanoreduz on c61_reduz = reduzido_debito
                                                and c61_anousu = (select fc_getsession(\'db_anousu\')::int)
                              join conplano on c60_codcon = c61_codcon
                                           and c60_anousu = (select fc_getsession(\'db_anousu\')::int)
                       where erro is true;
                    ",
                    "correcao": ""
                  }
              }
'
     );


SQL_UP
);

    }


    public function down()
    {
        $this->execute("delete from consistenciasistema where db160_sequencial = 1000001;");
    }
}
