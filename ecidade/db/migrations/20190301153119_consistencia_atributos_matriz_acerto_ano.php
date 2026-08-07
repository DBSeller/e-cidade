<?php

use Classes\PostgresMigration;

class ConsistenciaAtributosMatrizAcertoAno extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

delete from consistenciasistema where db160_json ilike '%5c6aae34bee56%';

insert into consistenciasistema values
                    (nextval('consistenciasistema_db160_sequencial_seq'),
                    10,
                    '{
  "uuid": "5c6aae34bee56",
  "tipo": 1,
  "nome": "Diferenças entre atributos padrão MSC e E-Cidade",
  "descricao": "Demonstra as contas com diferença entre as configurações padrões da MSC e o que está atualmente configurado no E-Cidade.",
  "formulario": {
    "campos": [
      {
        "propriedade": "conta",
        "nome": "Estrutural da conta",
        "chave_primaria": true
      },
      {
        "propriedade": "atributos",
        "nome": "Atributos Configurados no E-Cidade"
      },
      {
        "propriedade": "padrao",
        "nome": "Atributos para MSC"
      }
    ]
  },
  "sql": {
    "consistencia": "
        select conta, array_to_string(atributos, \', \') as atributos, array_to_string(padrao, \', \') as padrao
          from (select conta,
                       (select array_accum(c121_sigla::text) as atributos
                          from (select distinct c121_sequencial, c121_sigla
                                  from conplano
                                       inner join conplanoatributos on c120_conplano = c60_codcon and c120_anousu = c60_anousu
                                       inner join conplanoinfocomplementar on c121_sequencial = c120_infocomplementar
                                 where c120_conplanosistema = 1
                                   and c60_anousu = fc_getsession(\'DB_anousu\')::integer
                                   and substr(c60_estrut, 1, 9) = conta
                                 order by c121_sequencial) as y) as atributos,
                       array_remove(
                         array[tipo1::text, tipo2::text, tipo3::text, tipo4::text, tipo5::text, tipo6::text], \'\' ) as padrao
              from atributos_padrao_msc2019 ) as x
        where (atributos @> padrao) is false
        order by conta;",

    "correcao" : "
      drop table if exists w_consistencia_msc_2019;
      create table w_consistencia_msc_2019 as
      select conta, array_to_string(atributos, \',\') as atributos, array_to_string(padrao, \',\') as padrao
          from (select conta,
                       (select array_accum(c121_sigla::text) as atributos
                          from (select distinct c121_sequencial, c121_sigla
                                  from conplano
                                       inner join conplanoatributos on c120_conplano = c60_codcon and c120_anousu = c60_anousu
                                       inner join conplanoinfocomplementar on c121_sequencial = c120_infocomplementar
                                 where c120_conplanosistema = 1
                                   and c60_anousu = fc_getsession(\'DB_anousu\')::integer
                                   and substr(c60_estrut, 1, 9) = conta
                                 order by c121_sequencial) as y) as atributos,
                       array_remove(
                         array[tipo1::text, tipo2::text, tipo3::text, tipo4::text, tipo5::text, tipo6::text], \'\' ) as padrao
              from atributos_padrao_msc2019 ) as x
        where (atributos @> padrao) is false
        order by conta;

      delete
        from conplanoatributos using conplano, w_consistencia_msc_2019
       where conplanoatributos.c120_conplano = conplano.c60_codcon
         and conplanoatributos.c120_anousu = conplano.c60_anousu
         and substr(conplano.c60_estrut, 1, 9) = conta
         and conplanoatributos.c120_anousu >= fc_getsession(\'DB_anousu\')::integer
         and conplanoatributos.c120_conplanosistema = 1;

      insert into conplanoatributos
      select nextval(\'conplanoatributos_c120_sequencial_seq\'),
             c60_anousu,
             c60_codcon,
             c121_sequencial,
             1
       from atributos_padrao_msc2019
            join conplanoinfocomplementar on conplanoinfocomplementar.c121_sigla = atributos_padrao_msc2019.tipo1
                                          or conplanoinfocomplementar.c121_sigla = atributos_padrao_msc2019.tipo2
                                          or conplanoinfocomplementar.c121_sigla = atributos_padrao_msc2019.tipo3
                                          or conplanoinfocomplementar.c121_sigla = atributos_padrao_msc2019.tipo4
                                          or conplanoinfocomplementar.c121_sigla = atributos_padrao_msc2019.tipo5
                                          or conplanoinfocomplementar.c121_sigla = atributos_padrao_msc2019.tipo6
            join conplano on substr(conplano.c60_estrut, 1, 9) = atributos_padrao_msc2019.conta
            join w_consistencia_msc_2019 on w_consistencia_msc_2019.conta = atributos_padrao_msc2019.conta
      where c60_anousu >= fc_getsession(\'DB_anousu\')::integer
        and c121_sequencial in (1, 2, 3, 4, 5, 6, 7, 8, 9, 50, 51)
      order by c121_sequencial, c60_anousu;
    "
  }
}' );

SQL_UP
);

    }

    public function down()
    {

        $this->execute("delete from consistenciasistema where db160_json ilike '%5c6aae34bee56%';");
    }
}
