<?php

use Classes\PostgresMigration;

class M12259SiconfiDotacaoFinalidade extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP
        
insert into db_sysarquivo values (1010408, 'siconfidotacaofinalidade', 'siconfidotacaofinalidade', 'c119', '2019-01-28', 'siconfidotacaofinalidade', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,1010408);

insert into db_syscampo values (1009577,'c119_sequencial','int4','Código Sequencial','0', 'Código Sequencial',10,'f', 'f', 'f', '1', 'text','Código Sequencial');
insert into db_syscampo values(1010318,'c119_coddot','int4','Código da Dotação','0', 'Código da Dotação',10,'f','f','f',1,'text','Código da Dotação');
insert into db_syscampo values(1010319,'c119_anousu','int4','Ano da Dotação','0', 'Ano da Dotação',10,'f','f','f',1,'text','Ano da Dotação');
insert into db_syscampo values(1010320,'c119_tipo','int4','Tipo de Finalidade','0', 'Tipo de Finalidade',10,'f','f','f',1,'text','Tipo de Finalidade');
delete from db_sysarqcamp where codarq = 1010408;
insert into db_sysarqcamp values(1010408,1009577,1,0);
insert into db_sysarqcamp values(1010408,1010318,2,0);
insert into db_sysarqcamp values(1010408,1010319,3,0);
insert into db_sysarqcamp values(1010408,1010320,4,0);
delete from db_sysforkey where codarq = 1010408 and referen = 0;
insert into db_sysforkey values(1010408,1010319,1,758,0);
insert into db_sysforkey values(1010408,1010318,2,758,0);
delete from db_sysprikey where codarq = 1010408;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010408,1010318,1,1009577);
insert into db_sysindices values(1008429,'siconfidotacaofinalidade_dotacao_in',1010408,'1');
insert into db_syscadind values(1008429,1010318,1);
insert into db_syscadind values(1008429,1010319,2);
insert into db_syssequencia values(1000815, 'siconfidotacaofinalidade_c119_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000815 where codarq = 1010408 and codcam = 1009577;

create table siconfidotacaofinalidade(
 c119_sequencial serial not null primary key ,
 c119_coddot integer not null,
 c119_anousu integer not null,
 c119_tipo   integer not null 
);
create unique index siconfidotacaofinalidade_dotacao_in on siconfidotacaofinalidade(c119_anousu, c119_coddot);
alter table siconfidotacaofinalidade add constraint siconfidotacaofinalidade_dotacao_fk foreign key (c119_anousu, c119_coddot) references orcdotacao;

SQL_UP
);
    }


    public function down()
    {
        $this->execute(<<<SQL_DOWN
        
drop table if exists siconfidotacaofinalidade;

delete from db_syssequencia where codsequencia = 1000815;
delete from db_syscadind where codind = 1008429;
delete from db_sysindices where codind = 1008429;
delete from db_sysprikey where codarq = 1010408;
delete from db_sysforkey where codarq = 1010408;
delete from db_sysarqcamp where codarq = 1010408;
delete from db_syscampo where codcam in (1010318, 1010319, 1010320, 1009577);
delete from db_sysarqmod where codarq = 1010408;
delete from db_sysarquivo where codarq = 1010408;





SQL_DOWN
);
    }

}
