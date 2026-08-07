<?php

use Classes\PostgresMigration;

class M11930SolicitacoesVinculosItens extends PostgresMigration
{
    public function up()
    {
        $this->execute(
          <<<SQL_UP
create table bkp_solicitapai_11930 as
      select pc53_solicitapai as solicita_pai, pc10_numero as solicita_filho, pc11_codigo as solicitem_filho, pc10_solicitacaotipo
        from solicita
             inner join solicitem on pc11_numero = pc10_numero
             inner join solicitavinculo on pc53_solicitafilho = pc10_numero
       where not exists(select 1
                          from solicitemvinculo
                         where pc11_codigo = pc55_solicitemfilho)
         and pc10_solicitacaotipo = 6
         and extract(year from pc10_data) in(2017, 2018)
       order by 1;

create table bkp_solicita_primeiro_filho_11930 as
      select distinct bkp_solicitapai_11930.solicita_pai as solicita_pai, min(pc53_solicitafilho) as solicita_filho
        from solicitavinculo
             inner join bkp_solicitapai_11930 on bkp_solicitapai_11930.solicita_pai = solicitavinculo.pc53_solicitapai
       group by bkp_solicitapai_11930.solicita_pai
       order by 1;

update bkp_solicitapai_11930
   set solicita_pai = bkp_solicita_primeiro_filho_11930.solicita_filho
  from bkp_solicita_primeiro_filho_11930
 where bkp_solicita_primeiro_filho_11930.solicita_pai = bkp_solicitapai_11930.solicita_pai;

insert into solicitemvinculo
     select nextval('solicitemvinculo_pc55_sequencial_seq'), pc11_codigo, solicitem_filho
       from solicitem
            inner join bkp_solicitapai_11930 on solicita_pai = pc11_numero;

drop table bkp_solicita_primeiro_filho_11930;
drop table bkp_solicitapai_11930;

SQL_UP
        );
    }

    public function down() {

    }
}
