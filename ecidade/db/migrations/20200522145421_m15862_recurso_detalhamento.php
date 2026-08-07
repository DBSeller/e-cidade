<?php

use Classes\PostgresMigration;

class M15862RecursoDetalhamento extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL_UP

insert into db_syscampo values(1011298,'o203_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1011299,'o203_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1011300,'o203_descricao','varchar(200)','Descrição','', 'Descrição',200,'f','t','f',0,'text','Descrição');
insert into db_syscampo values(1011301,'o203_estado','varchar(2)','Estado','', 'Estado',2,'f','t','f',0,'text','Estado');
insert into db_sysarquivo values (1010566, 'recursodetalhamento', 'recursodetalhamento', 'o203', '2020-05-22', 'recursodetalhamento', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010566);
delete from db_sysarqcamp where codarq = 1010566;
insert into db_sysarqcamp values(1010566,1011298,1,0);
insert into db_sysarqcamp values(1010566,1011299,2,0);
insert into db_sysarqcamp values(1010566,1011300,3,0);
insert into db_sysarqcamp values(1010566,1011301,4,0);
delete from db_sysprikey where codarq = 1010566;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010566,1011298,1,1011300);
insert into db_syssequencia values(1000914, 'recursodetalhamento_o203_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000914 where codarq = 1010566 and codcam = 1011298;
insert into db_sysindices values(1008574,'recursodetalhamento_codigo_in',1010566,'0');
insert into db_syscadind values(1008574,1011299,1);

create table orcamento.recursodetalhamento(
    o203_sequencial serial primary key not null,
    o203_codigo integer not null,
    o203_descricao varchar not null,
    o203_estado varchar(2) not null
);
create index recursodetalhamento_codigo_in on orcamento.recursodetalhamento(o203_codigo);

insert into orcamento.recursodetalhamento values (1, 0, 'Sem Detalhamento', 'RJ');
insert into orcamento.recursodetalhamento values (2, 1, 'Cadastro', 'RJ');
insert into orcamento.recursodetalhamento values (3, 2, 'Operação de Crédito', 'RJ');
insert into orcamento.recursodetalhamento values (4, 3, 'Convênio', 'RJ');

select setval('recursodetalhamento_o203_sequencial_seq', 10000);

SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

drop table if exists orcamento.recursodetalhamento;
delete from db_sysforkey where codarq = 1010566;
delete from db_sysindices where codarq = 1010566;
delete from db_syscadind where codcam in (1011298,1011299,1011300,1011301);
delete from db_sysprikey where codarq = 1010566;
delete from db_syssequencia where codsequencia = 1000914;
delete from db_sysarqcamp where codarq = 1010566;
delete from db_sysarqmod where codarq = 1010566;
delete from db_sysarquivo where codarq = 1010566;
delete from db_syscampo where codcam in (1011298,1011299,1011300,1011301);



SQL_DOWN
);
    }
}
