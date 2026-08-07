<?php

use Classes\PostgresMigration;

class M12258DombaAlteracaoEstrutura extends PostgresMigration
{

    public function up()
    {

        $this->execute("select fc_startsession();");
        $this->execute("select fc_putsession('DB_datausu', '2018-12-26');");
        $this->execute("select fc_putsession('DB_anousu', '2018');");
        $this->execute(<<<SQL_UP

alter table conplanocontabancaria add c56_reduz integer;
alter table conplanoconta         add c63_reduz integer;



create table backup_conplanocontabancaria_sem_reduzido as
  select conplanocontabancaria.*
  from conplanocontabancaria
         left join conplanoreduz on c61_codcon = c56_codcon
                                      and c61_anousu = c56_anousu

  where (c61_reduz is null)
  order by c56_anousu;

delete
from conplanocontabancaria
    using backup_conplanocontabancaria_sem_reduzido
where backup_conplanocontabancaria_sem_reduzido.c56_codcon = conplanocontabancaria.c56_codcon
  and backup_conplanocontabancaria_sem_reduzido.c56_anousu = conplanocontabancaria.c56_anousu;

update conplanocontabancaria
set c56_reduz = c61_reduz
from conplanoreduz
where c61_codcon = c56_codcon
  and c61_anousu = c56_anousu ;

create table backup_conplano_conta_sem_contabancaria as
  select conplanoconta.*
  from conplanoconta
         left join conplanoreduz on c61_codcon = c63_codcon
                                      and c61_anousu = c63_anousu

  where (c61_reduz is null)
  order by c63_anousu;



delete
from conplanoconta
    using backup_conplano_conta_sem_contabancaria
where backup_conplano_conta_sem_contabancaria.c63_codcon = conplanoconta.c63_codcon
  and backup_conplano_conta_sem_contabancaria.c63_anousu = conplanoconta.c63_anousu;

update conplanoconta
set c63_reduz = c61_reduz
from conplanoreduz
where c61_codcon = c63_codcon
  and c61_anousu = c63_anousu;

drop index if exists conplanocontabancaria_con_ano_cta_in;
create unique index conplanocontabancaria_con_ano_cta_in on conplanocontabancaria (c56_contabancaria, c56_codcon, c56_anousu, c56_reduz);
alter table conplanocontabancaria add constraint conplanocontabancaria_reduz_fk foreign key (c56_reduz, c56_anousu) references conplanoreduz;

alter table conplanoconta drop CONSTRAINT conplanoconta_codc_ae_pk;
alter table conplanoconta add constraint conplanoconta_codc_ae_pk primary key (c63_codcon, c63_anousu, c63_reduz);
alter table conplanoconta add constraint conplanoconta_reduz_fk foreign key (c63_reduz, c63_anousu) references conplanoreduz;

alter table conplanoconta alter column c63_reduz set not null;
alter table conplanocontabancaria alter column c56_reduz set not null;

SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN



alter table conplanoconta drop CONSTRAINT conplanoconta_codc_ae_pk;
alter table conplanocontabancaria drop column c56_reduz;
alter table conplanoconta drop column c63_reduz;
create unique index conplanocontabancaria_con_ano_cta_in on conplanocontabancaria (c56_contabancaria, c56_codcon, c56_anousu);
alter table conplanoconta add constraint conplanoconta_codc_ae_pk primary key (c63_codcon, c63_anousu);

SQL_DOWN
);

    }


}
