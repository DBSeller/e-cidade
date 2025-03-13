<?php

use Classes\PostgresMigration;

class M15862RecursoIdentificador extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

insert into db_syscampo values(1011294,'o202_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1011295,'o202_codigo','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1011296,'o202_descricao','varchar(200)','Descrição','', 'Descrição',200,'f','t','f',0,'text','Descrição');
insert into db_syscampo values(1011297,'o202_estado','varchar(2)','Estado','', 'Estado',2,'f','t','f',0,'text','Estado');
insert into db_sysarquivo values (1010565, 'recursoidentificador', 'recursoidentificador', 'o202', '2020-05-21', 'recursoidentificador', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010565);
delete from db_sysarqcamp where codarq = 1010565;
insert into db_sysarqcamp values(1010565,1011294,1,0);
insert into db_sysarqcamp values(1010565,1011295,2,0);
insert into db_sysarqcamp values(1010565,1011296,3,0);
insert into db_sysarqcamp values(1010565,1011297,4,0);
delete from db_sysprikey where codarq = 1010565;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010565,1011294,1,1011296);
insert into db_sysindices values(1008573,'recursoidentificador_codigo_in',1010565,'0');
insert into db_syscadind values(1008573,1011295,1);
insert into db_syssequencia values(1000913, 'recursoidentificador_o202_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000913 where codarq = 1010565 and codcam = 1011294;

create table orcamento.recursoidentificador(
    o202_sequencial serial primary key not null,
    o202_codigo integer not null,
    o202_descricao varchar not null,
    o202_estado varchar(2) not null
);
create index recursoidentificador_codigo_in on orcamento.recursoidentificador(o202_codigo);

insert into orcamento.recursoidentificador values (1, 0, 'Recursos não destinados à contrapartida ou à identificação de despesas destinadas ao mínimo da Saúde ou ao mínimo da Educação', 'RJ');
insert into orcamento.recursoidentificador values (2, 1, 'Contrapartida de empréstimos do BIRD', 'RJ');
insert into orcamento.recursoidentificador values (3, 2, 'Contrapartida de empréstimos do BID', 'RJ');
insert into orcamento.recursoidentificador values (4, 3, 'Contrapartida de empréstimos do CAF', 'RJ');
insert into orcamento.recursoidentificador values (5, 4, 'Contrapartida de outros empréstimos', 'RJ');
insert into orcamento.recursoidentificador values (6, 5, 'Contrapartida de doações', 'RJ');
insert into orcamento.recursoidentificador values (7, 6, 'Recursos não destinados à contrapartida, para identificação das despesas destinadas ao mínimo da Saúde', 'RJ');
insert into orcamento.recursoidentificador values (8, 7, 'Recursos de Contrapartida de Convênio', 'RJ');
insert into orcamento.recursoidentificador values (9, 8, 'Recursos não destinados à contrapartida, para identificação das despesas destinadas ao mínimo da Educação', 'RJ');

select setval('recursoidentificador_o202_sequencial_seq', 10000);

SQL_UP
);
    }


    public function down()
    {

        $this->execute(<<<SQL_DOWN

drop table if exists orcamento.recursoidentificador;
delete from db_sysforkey where codarq = 1010565;
delete from db_sysindices where codarq = 1010565;
delete from db_syscadind where codcam in (1011294,1011295,1011296,1011297);
delete from db_sysprikey where codarq = 1010565;
delete from db_syssequencia where codsequencia = 1000913;
delete from db_sysarqcamp where codarq = 1010565;
delete from db_sysarqmod where codarq = 1010565;
delete from db_sysarquivo where codarq = 1010565;
delete from db_syscampo where codcam in (1011294,1011295,1011296,1011297);


SQL_DOWN
);
    }
}
