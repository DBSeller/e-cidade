<?php

use Classes\PostgresMigration;

class M14143ConsistenciaAtributosVazios extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_DOWN

delete from consistenciasistema where db160_json ilike '%5d7661b3b497d5d7661b3b497d%';

insert into consistenciasistema
values (nextval('consistenciasistema_db160_sequencial_seq'),
        10,
        '{
"tipo": 1,
"uuid": "5d7661b3b497d5d7661b3b497d",
"nome": "Lançamentos que possuem atributos Vazio ou NI.",
"descricao": "Lançamentos que possuem atributos e não foram encontrados valores.",
"formulario": {
"campos": [
  {
    "propriedade": "lancamento",
    "nome": "Lançamento",
    "width": "10",
    "chave_primaria": true
  },
  {
    "propriedade": "documento",
    "nome": "Documento",
    "width": "30",
    "chave_primaria": false
  },
  {
    "propriedade": "mensagem_log",
    "nome": "Log",
    "width": "55",
    "chave_primaria": false
  }
]
},
"filtros": {
"campos": [
  {
    "label": "Data Inicial",
    "nome": "data_inicial",
    "tipo": "data"
  },
  {
    "label": "Data Final",
    "nome": "data_final",
    "tipo": "data"
  }
]
},
"sql": {
"consistencia": "select c134_codlan as lancamento,
                        c53_coddoc ||\' - \'|| c53_descr as documento,
                        replace(c134_mensagem, \'\\\\\\\\n\', \'<br>\') as mensagem_log
                       from conlancamlogatributos
                            join conlancamdoc on c71_codlan = c134_codlan
                            join conhistdoc on c53_coddoc = c71_coddoc
                      where c71_data between \'#data_inicial#\' and \'#data_final#\';",
    "correcao": ""
  }
}
');


SQL_DOWN
);
    }

    public function down()
    {
        $this->execute("delete from consistenciasistema where db160_json ilike '%5d7661b3b497d5d7661b3b497d%';");
    }
}
