<?php

use Classes\PostgresMigration;

class M13950NovaConsitenciaMatriz extends PostgresMigration
{

    public function up()
    {

        $this->execute(
            <<<SQL_UP

delete from consistenciasistema where db160_json ilike '%5d35a3c9aac5a%';

insert into consistenciasistema
values ( nextval('consistenciasistema_db160_sequencial_seq'), 10,
    '{
  "tipo": 1,
  "uuid": "5d35a3c9aac5a",
  "nome": "Contas existentes no E-cidade e Não existentes na MSC 2019 (sem validar atributos)",
  "descricao": "Demonstra as contas que estão abertas no E-Cidade e não existem no layout importado no e-cidade.",
  "formulario": {
    "campos": [
      {
        "propriedade": "estrut",
        "nome": "Estrutural Pesquisado",
        "chave_primaria": true
      }      
    ]
  },
  "filtros": {
    "campos": [
      {
        "label": "Estrutural",
        "nome": "estrutural",
        "tipo": "string"
      }
    ]
  },
  "sql": {
    "consistencia": "select distinct substr(c60_estrut, 1, 9) as estrut
  from conplano
       left join atributos_padrao_msc2019 on conta = substr(c60_estrut, 1, 9)
 where c60_anousu = 2019
   and c60_estrut ilike \'#estrutural#%\'
   and conta is null order by estrut",
    "correcao": ""
  }
}'
);


SQL_UP
        );
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from consistenciasistema where db160_json ilike '%5d35a3c9aac5a%';

SQL_DOWN
);
    }
}
