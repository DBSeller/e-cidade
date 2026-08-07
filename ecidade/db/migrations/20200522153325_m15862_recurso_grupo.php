<?php

use Classes\PostgresMigration;

class M15862RecursoGrupo extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP

insert into db_syscampo values(1011302,'o204_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1011303,'o204_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1011304,'o204_descricao','varchar(200)','Descrição','', 'Descrição',200,'f','t','f',0,'text','Descrição');
insert into db_syscampo values(1011305,'o204_estado','varchar(2)','Estado','', 'Estado',2,'f','t','f',0,'text','Estado');
insert into db_sysarquivo values (1010568, 'recursogrupo', 'recursogrupo', 'o204', '2020-05-22', 'recursogrupo', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010568);
delete from db_sysarqcamp where codarq = 1010568;
insert into db_sysarqcamp values(1010568,1011302,1,0);
insert into db_sysarqcamp values(1010568,1011303,2,0);
insert into db_sysarqcamp values(1010568,1011304,3,0);
insert into db_sysarqcamp values(1010568,1011305,4,0);
insert into db_syssequencia values(1000915, 'recursogrupo_o204_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000915 where codarq = 1010568 and codcam = 1011302;
delete from db_sysprikey where codarq = 1010568;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010568,1011302,1,1011304);
insert into db_sysindices values(1008575,'recursogrupo_codigo_in',1010568,'0');
insert into db_syscadind values(1008575,1011303,1);

create table orcamento.recursogrupo(
    o204_sequencial serial primary key not null,
    o204_codigo integer not null,
    o204_descricao varchar not null,
    o204_estado varchar(2) not null
);
create index recursogrupo_codigo_in on orcamento.recursogrupo(o204_codigo);

insert into orcamento.recursogrupo values (1, 1, 'Recursos do Tesouro - Exercício Corrente', 'RJ');
insert into orcamento.recursogrupo values (2, 2, 'Recursos de Outras Fontes - Exercício Corrente', 'RJ');

select setval('recursogrupo_o204_sequencial_seq', 10000);



SQL_UP
);
    }


    public function down()
    {
        $this->execute(<<<SQL_DOWN


drop table if exists orcamento.recursogrupo;
delete from db_sysforkey where codarq = 1010568;
delete from db_sysindices where codarq = 1010568;
delete from db_syscadind where codcam in (1011302,1011303,1011304,1011305);
delete from db_sysprikey where codarq = 1010568;
delete from db_syssequencia where codsequencia = 1000915;
delete from db_sysarqcamp where codarq = 1010568;
delete from db_sysarqmod where codarq = 1010568;
delete from db_sysarquivo where codarq = 1010568;
delete from db_syscampo where codcam in (1011302,1011303,1011304,1011305);




SQL_DOWN
);
    }
}
