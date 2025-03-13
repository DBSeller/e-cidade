<?php

use Classes\PostgresMigration;

class M13822ConsistenciaDocumento1010 extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP


insert into consistenciasistema
   values (1000000, 100, '{
  "tipo": 100,
  "uid": "5dd711a05ef67",
  "nome": "Encerramento da Natureza Orçamentaria e Controle - Doc. 1010/1020/1021",
  "descricao": "Demonstra as contas e os saldos do balancete e o que vai ser processado para execução do encerramento.",
  "formulario": {
    "campos": [
        {
          "propriedade": "conta",
          "nome": "Reduzido"
        },
        {
          "propriedade": "descricao_conta",
          "nome": "Descrição da Conta",
          "chave_primaria": true
        },
        {
          "propriedade": "valor_consistencia",
          "nome": "Valor Encontrado"
        },
        {
          "propriedade": "saldo_balancete",
          "nome": "Saldo do Balancete de Verificação"
        }
      ]
    },
    "sql": {
      "consistencia": "
      drop table if exists bkp_consistencia_1010;
drop table if exists bkp_consistencia_1010_1;
drop table if exists bkp_consistencia_1010_2;

create table bkp_consistencia_1010 as select * from fc_encerramento_doc_1010(1010);
alter table bkp_consistencia_1010 add column inverter_conta boolean default false;
update bkp_consistencia_1010 set inverter_conta = false;
delete from bkp_consistencia_1010 where estrutural ilike \'9%\';
create table bkp_consistencia_1010_1 as select * from fc_encerramento_doc_1010(1010) where substring(estrutural,1,1) = \'9\' and valor < 0;
update bkp_consistencia_1010_1 set valor = valor*-1;
alter table bkp_consistencia_1010_1 add column inverter_conta boolean default false;
update bkp_consistencia_1010_1 set inverter_conta = true;
create table bkp_consistencia_1010_2 as select * from fc_encerramento_doc_1010(1010) where substring(estrutural,1,1) = \'9\' and valor > 0;
update bkp_consistencia_1010_2 set valor = valor*-1;
alter table bkp_consistencia_1010_2 add column inverter_conta boolean default false;
update bkp_consistencia_1010_2 set inverter_conta = false;
insert into bkp_consistencia_1010 select * from bkp_consistencia_1010_1;
insert into bkp_consistencia_1010 select * from bkp_consistencia_1010_2;

select * from (
                  select conta_credito as conta,
                         c60_estrut|| \' - \'||c60_descr as descricao_conta,
             abs(round(sum(valor)::numeric, 2)) as valor_consistencia,
             abs(round((select balancete[4]
                    from (select *
                          from fc_planosaldonovo_array(
                                       (select fc_getsession(\'db_anousu\'))::int,
                                       conta_credito,
                                       (select fc_getsession(\'db_anousu\')||\'-01-01\')::date,
                                       (select fc_getsession(\'db_anousu\')||\'-12-31\')::date, false)
                                   as balancete) as x )::numeric, 2)) as saldo_balancete
                  from bkp_consistencia_1010
                      join conplanoreduz
                  on c61_reduz = conta_credito
                      and c61_anousu = ano
                      join conplano on c60_codcon = c61_codcon
                      and c60_anousu = c61_anousu
                  where compara = 2
                  group by 1, 2

                  union

                  select conta_debito as conta,
                      c60_estrut|| \' - \'||c60_descr as descricao_conta,
                      abs(round(sum(valor)::numeric, 2)) as valor_consistencia,
                      abs(round((select balancete[4]
                      from (select *
                      from fc_planosaldonovo_array(
                      (select fc_getsession(\'db_anousu\'))::int,
                      conta_debito,
                      (select fc_getsession(\'db_anousu\')||\'-01-01\')::date,
                      (select fc_getsession(\'db_anousu\')||\'-12-31\')::date, false)
                      as balancete) as x )::numeric, 2)) as saldo_balancete
                  from bkp_consistencia_1010
                      join conplanoreduz
                  on c61_reduz = conta_credito
                      and c61_anousu = ano
                      join conplano on c60_codcon = c61_codcon
                      and c60_anousu = c61_anousu
                  where compara = 1
                  group by 1, 2

                  union

                  select conta_debito as conta,
                      c60_estrut|| \' - \'||c60_descr as descricao_conta,
                      abs(round(sum(case when substring (estrutural, 1, 1) = \'9\' then valor*-1 else valor end), 2)) as valor_consistencia,
                      abs(round((select balancete[4]
                      from (select *
                      from fc_planosaldonovo_array(
                      (select fc_getsession(\'db_anousu\'))::int,
                      conta_debito,
                      (select fc_getsession(\'db_anousu\')||\'-01-01\')::date,
                      (select fc_getsession(\'db_anousu\')||\'-12-31\')::date, false)
                      as balancete) as x )::numeric, 2)) as saldo_balancete
                  from fc_encerramento_doc_1010(1020)
                      join conplanoreduz
                  on c61_reduz = conta_credito
                      and c61_anousu = ano
                      join conplano on c60_codcon = c61_codcon
                      and c60_anousu = c61_anousu
                  group by 1, 2

                  union

                  select conta_debito as conta,
                      c60_estrut|| \' - \'||c60_descr as descricao_conta,
                      abs(round(sum(case when substring (estrutural, 1, 1) = \'9\' then valor*-1 else valor end), 2)) as valor_consistencia,
                      abs(round((select balancete[4]
                      from (select *
                      from fc_planosaldonovo_array(
                      (select fc_getsession(\'db_anousu\'))::int,
                      conta_debito,
                      (select fc_getsession(\'db_anousu\')||\'-01-01\')::date,
                      (select fc_getsession(\'db_anousu\')||\'-12-31\')::date, false)
                      as balancete) as x )::numeric, 2)) as saldo_balancete
                  from fc_encerramento_doc_1010(1021)
                      join conplanoreduz
                  on c61_reduz = conta_credito
                      and c61_anousu = ano
                      join conplano on c60_codcon = c61_codcon
                      and c60_anousu = c61_anousu
                  group by 1, 2
              ) as resultado where valor_consistencia <> saldo_balancete;
      
      
      ",
      "correcao": ""
    }
}

');


SQL_UP
);
    }


    public function down()
    {

        $this->execute(<<<SQL_DOWN
delete from consistenciasistema where db160_sequencial = 1000000;
SQL_DOWN
);
    }
}
