<?php

use Classes\PostgresMigration;

class M14846AcertoLiquidacao extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {

        $this->execute(<<<SQL
create temp table w_acerto_empenhos_412 as
select (case when substr(o56_elemento, 1, 2) = '34' and e45_numemp is null then 23
            when e45_numemp is not null then 412 end) as documento,
                  c71_codlan as lancamento,
                  c71_coddoc,
                  e60_emiss,
                  o56_elemento,
                  e60_numemp
  from contabilidade.conlancamemp
         inner join contabilidade.conlancamdoc on c75_codlan = c71_codlan
         inner join empenho.empempenho on c75_numemp = e60_numemp
         inner join empenho.empelemento on empempenho.e60_numemp = empelemento.e64_numemp
         inner join orcamento.orcelemento on empelemento.e64_codele = o56_codele and e60_anousu = o56_anousu
         left join empenho.emppresta on e45_numemp = e60_numemp
where c71_coddoc = 3
  and (substr(o56_elemento, 1, 2) = '34' or e45_numemp is not null)
  and e60_anousu = 2019
  and c71_data >= '2019-11-26' and  c71_data <= '2019-11-30'
order by 2 ;

update contabilidade.conlancamdoc
   set c71_coddoc = w_acerto_empenhos_412.documento
  from w_acerto_empenhos_412
where c71_codlan = lancamento;

-- ajusta os estornos
create temp table w_acerto_empenhos_413 as
select (case when substr(o56_elemento, 1, 2) = '34' and e45_numemp is null then 24
             when e45_numemp is not null then 413 end) as documento,
       c71_codlan as lancamento,
       c71_coddoc,
       e60_emiss,
       c71_data,
       o56_elemento,
       e60_numemp
from contabilidade.conlancamemp
         inner join contabilidade.conlancamdoc on c75_codlan = c71_codlan
         inner join empenho.empempenho on c75_numemp = e60_numemp
         inner join empenho.empelemento on empempenho.e60_numemp = empelemento.e64_numemp
         inner join orcamento.orcelemento on empelemento.e64_codele = o56_codele and e60_anousu = o56_anousu
         left join empenho.emppresta on e45_numemp = e60_numemp
where c71_coddoc = 24
  and (substr(o56_elemento, 1, 2) = '34' or e45_numemp is not null)
  and e60_anousu = 2019
  and c71_data >= '2019-11-26' and  c71_data <= '2019-11-30'
order by 2 ;

update contabilidade.conlancamdoc
   set c71_coddoc = w_acerto_empenhos_413.documento
  from w_acerto_empenhos_413
 where c71_codlan = lancamento;

rollback;
begin;
drop table if exists  w_acerto_empenhos_doc_90;
create table w_acerto_empenhos_doc_90 as
select c71_codlan as lancamento,
       c71_coddoc,
       e60_emiss,
       c71_data,
       o56_elemento,
       e60_numemp,
       e60_numcgm,
       e64_codele,
       e60_coddot,
       c70_valor,
       e60_instit,
       e45_tipo,
       nextval('conlancam_c70_codlan_seq') as codigo_lancamento
from contabilidade.conlancamemp
         inner join contabilidade.conlancamdoc on c75_codlan = c71_codlan
         inner join contabilidade.conlancam on c75_codlan = c70_codlan
         inner join empenho.empempenho    on c75_numemp = e60_numemp
         inner join empenho.empelemento   on empempenho.e60_numemp = empelemento.e64_numemp
         inner join orcamento.orcelemento on empelemento.e64_codele = o56_codele and e60_anousu = o56_anousu
         inner join empenho.emppresta     on e45_numemp = e60_numemp
where c71_coddoc = 5
  and not exists(select  1
                 from contabilidade.conlancamemp pagemp
                      inner join conlancamdoc docemp on docemp.c71_codlan = pagemp.c75_codlan
                 where pagemp.c75_numemp = e60_numemp
                   and docemp.c71_coddoc = 90
                  and  docemp.c71_data >= '2019-11-26' and  docemp.c71_data <= '2019-11-30'
      )
  and e60_anousu = 2019
  and c71_data >= '2019-11-26' and  c71_data <= '2019-11-30'
order by 2 ;


insert into contabilidade.conlancam  select codigo_lancamento, 2019, c71_data, c70_valor from w_acerto_empenhos_doc_90;

insert into contabilidade.conlancamval   select
                                       nextval('conlancamval_c69_sequen_seq'),
                                       2019,
                 codigo_lancamento,
                                       9005,
                                       (select c47_credito
                                          from contabilidade.contranslr
                                               inner join contabilidade.contranslan on c47_seqtranslan = c46_seqtranslan
                                               inner join contabilidade.contrans on c45_seqtrans = c46_seqtrans
                                         where c45_coddoc = 90 and c45_anousu = 2019
                                           and c45_instit = e60_instit
                                           and c47_ref = e45_tipo
                                        ),
                                        (select c47_debito
                                           from contabilidade.contranslr
                                                 inner join contabilidade.contranslan on c47_seqtranslan = c46_seqtranslan
                                                 inner join contabilidade.contrans on c45_seqtrans = c46_seqtrans
                                          where c45_coddoc = 90 and c45_anousu = 2019
                                            and c45_instit = e60_instit
                                            and c47_ref = e45_tipo
                                        ),
                                       c70_valor,
                                       c71_data
from w_acerto_empenhos_doc_90;

insert into contabilidade.conlancamdoc    select  codigo_lancamento, 90, c71_data from w_acerto_empenhos_doc_90;
insert into contabilidade.conlancamordem  select  nextval('conlancamordem_c03_sequencial_seq'), codigo_lancamento, codigo_lancamento from w_acerto_empenhos_doc_90;
insert into contabilidade.conlancamcgm    select codigo_lancamento, e60_numcgm, c71_data from w_acerto_empenhos_doc_90;
insert into contabilidade.conlancamele    select codigo_lancamento, e64_codele from  w_acerto_empenhos_doc_90;
insert into contabilidade.conlancamemp    select codigo_lancamento, e60_numemp, c71_data from  w_acerto_empenhos_doc_90;
insert into contabilidade.conlancamdot    select codigo_lancamento, 2019, e60_coddot, c71_data from  w_acerto_empenhos_doc_90;
insert into contabilidade.conlancaminstit select nextval('conlancaminstit_c02_sequencial_seq'), codigo_lancamento, e60_instit from w_acerto_empenhos_doc_90;

SQL
        );
    }

    public function down()
    {

    }
}
