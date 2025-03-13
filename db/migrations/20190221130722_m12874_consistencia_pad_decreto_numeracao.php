<?php

use Classes\PostgresMigration;

class M12874ConsistenciaPadDecretoNumeracao extends PostgresMigration
{
   public function up()
   {

       $this->execute(<<<SQL_UP

delete from consistenciasistema where db160_json ilike '%"5c6ea1e8149bc"%';

insert into consistenciasistema
     values (nextval('consistenciasistema_db160_sequencial_seq'), 1 ,
             '{
                "tipo": 1,
                "uuid" : "5c6ea1e8149bc",
                "nome": "Decretos sem numeração",
                "descricao": "Decretos sem númeração informada.",
                "formulario": {
                  "campos": [
                    {
                      "propriedade": "codigo_projeto",
                      "nome": "Código do Projeto",
                      "chave_primaria" : true
                    },
                    {
                      "propriedade": "descricao",
                      "nome": "Descrição"
                    },
                    {
                      "propriedade": "numero_encontrado",
                      "nome": "Número Encontrado"
                    },
                    {
                      "propriedade": "numero",
                      "nome": "Número",
                      "tipo": "input"
                    }
                  ]
                },
                "sql": {
                  "consistencia": "
              select o39_codproj as codigo_projeto,
                     o39_descr as descricao,
                     o39_numero as numero_encontrado,
                     o39_numero as numero
                from orcprojeto
               where regexp_replace(trim(orcprojeto.o39_numero), \'0\', \'\', \'g\') = \'\' ",
                  "correcao" : "update orcprojeto set o39_numero = \'[numero]\' where o39_codproj = [codigo_projeto];"
                }
              }'
             );

SQL_UP
);
   }

   public function down()
   {
       $this->execute("delete from consistenciasistema where db160_json ilike '%\"5c6ea1e8149bc\"%';");
   }
}
