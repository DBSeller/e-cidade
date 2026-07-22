<?php

use Classes\PostgresMigration;

class M11191UnidadeGestoraDepartamentos extends PostgresMigration
{

    public function up()
    {

        $this->execute(
            <<<SQL_UP
         
insert into db_sysarquivo values (1010336, 'unidadegestoradepartamentos', 'Departamentos da Unidade Gestora', 'k180', '2018-11-09', 'unidadegestoradepartamentos', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010336);
insert into db_syscampo values(1010070,'k180_sequencial','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1010071,'k180_unidadegestora','int4','Código da Unidade Gestora','0', 'Código da Unidade Gestora',10,'f','f','f',1,'text','Código da Unidade Gestora');
insert into db_syscampo values(1010072,'k180_depart','int4','Código do Departamento','0', 'Código do Departamento',10,'f','f','f',1,'text','Código do Departamento');
delete from db_sysarqcamp where codarq = 1010336;
insert into db_sysarqcamp values(1010336,1010070,1,0);
insert into db_sysarqcamp values(1010336,1010071,2,0);
insert into db_sysarqcamp values(1010336,1010072,3,0);
insert into db_syssequencia values(1000780, 'unidadegestoradepartamentos_k180_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000780 where codarq = 1010336 and codcam = 1010070;
delete from db_sysforkey where codarq = 1010336 and referen = 0;
insert into db_sysforkey values(1010336,1010071,1,4030,0);
delete from db_sysforkey where codarq = 1010336 and referen = 0;
insert into db_sysforkey values(1010336,1010072,1,154,0);
insert into db_sysindices values(1008347,'unidadegestoradepartamentos_unidadegestora_in',1010336,'0');
insert into db_syscadind values(1008347,1010071,1);
insert into db_sysindices values(1008348,'unidadegestoradepartamentos_depart_in',1010336,'0');
insert into db_syscadind values(1008348,1010072,1);
delete from db_sysprikey where codarq = 1010336;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010336,1010070,1,1010071);


create table caixa.unidadegestoradepartamentos (
  k180_sequencial serial not null,
  k180_unidadegestora integer not null,
  k180_depart integer not null,
  constraint unidadegestoradepartamentos_sequencial_pk primary key (k180_sequencial),
  constraint unidadegestoradepartamentos_unidadegestora_fk foreign key (k180_unidadegestora) references unidadegestora,
  constraint unidadegestoradepartamentos_db_depart_fk foreign key (k180_depart) references db_depart
);

create index unidadegestoradepartamentos_unidadegestora_in on caixa.unidadegestoradepartamentos(k180_unidadegestora);
create index unidadegestoradepartamentos_depart_in on caixa.unidadegestoradepartamentos(k180_depart);

SQL_UP
        );

    }

    public function down()
    {
        $this->execute(
            <<<SQL_DOWN

drop table caixa.unidadegestoradepartamentos;
delete from db_sysprikey where codarq = 1010336;
delete from db_syscadind where codcam in (1010070, 1010071, 1010072);
delete from db_sysindices where codarq = 1010336;
delete from db_sysforkey where codarq = 1010336;
delete from db_syssequencia where codsequencia = 1000780;
delete from db_sysarqcamp where codarq = 1010336;
delete from db_syscampo where codcam in (1010070, 1010071, 1010072);
delete from db_sysarqmod where codarq = 1010336;
delete from db_sysarquivo where codarq = 1010336;


SQL_DOWN
        );
    }

}
