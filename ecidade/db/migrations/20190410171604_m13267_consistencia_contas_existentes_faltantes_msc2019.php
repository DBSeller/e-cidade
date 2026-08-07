<?php

use Classes\PostgresMigration;

class M13267ConsistenciaContasExistentesFaltantesMsc2019 extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP

delete from consistenciasistema where db160_json ilike '%5cae05ba5cc9e%';

insert into consistenciasistema values
(nextval('consistenciasistema_db160_sequencial_seq'),
 10,
 '{
"uuid": "5cae05ba5cc9e",
"tipo": 1,
"nome": "Contas do E-cidade não existentes na Matriz de 2019",
"descricao": "Demonstra as contas existentes no E-Cidade que não existem na MSC disponibilizada pela União.",
"ajuda" : "Quando clicar em salvar o sistema irá vincular os atributos PO para todas as 
contas listadas. Para as contas do grupo 1 irá vincular também o atributo FR. 
Para as contas do grupo 2 irá vincular também o atributo FP. A correção irá 
aplicar para todos os anos igual ou superior ao ano do usuario",
"formulario": {
"campos": [
    {
    "propriedade": "conta",
    "nome": "Estrutural da conta",
    "chave_primaria": true
    },
    {
    "propriedade": "descricao",
    "nome": "Descrição"
    }
]
},
"sql": {
    "consistencia": "
        select c60_estrut as conta,
               c60_descr as descricao
          from conplano
               left join atributos_padrao_msc2019 on conta = substr(c60_estrut, 1, 9)
         where conplano.c60_anousu = fc_getsession(\'DB_anousu\')::integer
           and exists (select 1
                         from conplanoreduz
                        where conplanoreduz.c61_codcon = conplano.c60_codcon
                          and conplanoreduz.c61_anousu = conplano.c60_anousu)
           and not exists (select 1
                             from conplanoatributos
                            where conplanoatributos.c120_conplano = conplano.c60_codcon
                              and conplanoatributos.c120_anousu = conplano.c60_anousu
                              and conplanoatributos.c120_conplanosistema = 1)
           and conta is null
         order by c60_estrut;
    ",
    "correcao" : "

        drop table if exists w_conplano_atributos_padrao_msc_2019;

        create table w_conplano_atributos_padrao_msc_2019 as
              select distinct
                     c60_codcon,
                     c60_anousu,
                     c60_estrut,
                     c60_descr
                from conplano
                     left join atributos_padrao_msc2019 on conta = substr(c60_estrut, 1, 9)
               where conplano.c60_anousu >= fc_getsession(\'DB_anousu\')::integer
                 and exists (select 1
                               from conplanoreduz
                              where conplanoreduz.c61_codcon = conplano.c60_codcon
                                and conplanoreduz.c61_anousu = conplano.c60_anousu)
                 and not exists (select 1
                                   from conplanoatributos
                                  where conplanoatributos.c120_conplano = conplano.c60_codcon
                                    and conplanoatributos.c120_anousu = conplano.c60_anousu
                                    and conplanoatributos.c120_conplanosistema = 1)
                 and conta is null
               order by c60_estrut;


        insert into conplanoatributos
             select nextval(\'conplanoatributos_c120_sequencial_seq\'),
                    c60_anousu,
                    c60_codcon,
                    1,
                    1
               from w_conplano_atributos_padrao_msc_2019;

        insert into conplanoatributos
             select nextval(\'conplanoatributos_c120_sequencial_seq\'),
                    c60_anousu,
                    c60_codcon,
                    3,
                    1
               from w_conplano_atributos_padrao_msc_2019
              where substr(c60_estrut, 1, 1) = \'1\';

        insert into conplanoatributos
             select nextval(\'conplanoatributos_c120_sequencial_seq\'),
                    c60_anousu,
                    c60_codcon,
                    2,
                    1
              from w_conplano_atributos_padrao_msc_2019
             where substr(c60_estrut, 1, 1) = \'2\';


    "
  }
}');

SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN
delete from consistenciasistema where db160_json ilike '%5cae05ba5cc9e%';
SQL_DOWN
        );
    }

}
