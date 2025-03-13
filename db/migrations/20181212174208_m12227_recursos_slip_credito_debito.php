<?php

use Classes\PostgresMigration;

class M12227RecursosSlipCreditoDebito extends PostgresMigration
{
    public function up()
    {

        $this->execute(
            <<<SQL_UP
            
insert into db_sysarquivo values (1010354, 'sliprecursocontas', 'Recurso envolvidos com o slip', 'k181', '2018-12-12', 'sliprecursocontas', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010354);
insert into db_syscampo values(1010182,'k181_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1010183,'k181_slip','int4','Código do Slip','0', 'Código do Slip',10,'f','f','f',1,'text','Código do Slip');
insert into db_syscampo values(1010184,'k181_recursocredito','int4','Recurso a Crédito','0', 'Recurso a Crédito',10,'f','f','f',1,'text','Recurso a Crédito');
insert into db_syscampo values(1010185,'k181_recursodebito','int4','Recurso a Débito','0', 'Recurso a Débito',10,'f','f','f',1,'text','Recurso a Débito');
delete from db_sysarqcamp where codarq = 1010354;
insert into db_sysarqcamp values(1010354,1010182,1,0);
insert into db_sysarqcamp values(1010354,1010183,2,0);
insert into db_sysarqcamp values(1010354,1010184,3,0);
insert into db_sysarqcamp values(1010354,1010185,4,0);
delete from db_sysprikey where codarq = 1010354;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010354,1010182,1,1010183);
delete from db_sysforkey where codarq = 1010354 and referen = 0;
insert into db_sysforkey values(1010354,1010183,1,196,0);
delete from db_sysforkey where codarq = 1010354 and referen = 0;
insert into db_sysforkey values(1010354,1010184,1,749,0);
delete from db_sysforkey where codarq = 1010354 and referen = 0;
insert into db_sysforkey values(1010354,1010185,1,749,0);
insert into db_sysindices values(1008388,'sliprecursocontas_slip_in',1010354,'0');
insert into db_syscadind values(1008388,1010183,1);
insert into db_sysindices values(1008389,'sliprecursocontas_recursocredito_in',1010354,'0');
insert into db_syscadind values(1008389,1010184,1);
insert into db_sysindices values(1008390,'sliprecursocontas_recursodebito_in',1010354,'0');
insert into db_syscadind values(1008390,1010185,1);
insert into db_syssequencia values(1000796, 'sliprecursocontas_k181_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000796 where codarq = 1010354 and codcam = 1010182;



create table sliprecursocontas (
    k181_sequencial serial not null,
    k181_slip integer not null,
    k181_recursocredito integer not null,
    k181_recursodebito integer not null
);

alter table sliprecursocontas
      add constraint sliprecursocontas_slip_fk
      foreign key (k181_slip)
      references slip;

alter table sliprecursocontas
      add constraint sliprecursocontas_recursocredito_fk
      foreign key (k181_recursocredito)
      references orctiporec;

alter table sliprecursocontas
      add constraint sliprecursocontas_recursodebito_fk
      foreign key (k181_recursodebito)
      references orctiporec;
      
create index sliprecursocontas_slip_in on sliprecursocontas(k181_slip);
create index sliprecursocontas_recursocredito_in on sliprecursocontas(k181_recursocredito);
create index sliprecursocontas_recursodebito_in on sliprecursocontas(k181_recursodebito);

SQL_UP
        );
    }

    public function down()
    {

        $this->execute(
            <<<SQL_DOWN

delete from db_syssequencia where codsequencia = 1000796;
delete from db_syscadind where codcam in (1010182,1010183,1010184,1010185);
delete from db_sysindices where codarq = 1010354;
delete from db_sysforkey where codarq = 1010354;
delete from db_sysprikey where codarq = 1010354;
delete from db_sysarqcamp where codarq = 1010354;
delete from db_syscampo where codcam in (1010182,1010183,1010184,1010185);
delete from db_sysarqmod where codarq = 1010354;
delete from db_sysarquivo where codarq = 1010354;


drop table sliprecursocontas;

SQL_DOWN
        );

    }
}
