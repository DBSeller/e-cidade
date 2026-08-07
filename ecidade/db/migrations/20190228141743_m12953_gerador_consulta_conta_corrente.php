<?php

use Classes\PostgresMigration;

class M12953GeradorConsultaContaCorrente extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP
        
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228103 ,'Visões' ,'Gerador de Consulta para o Conta Corrente' ,'con4_criadorvisoescontacorrente001.php' ,'1' ,'1' ,'Gerador de Consulta para o Conta Corrente' ,'true' );
delete from db_menu where id_item_filho = 228103 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228063 ,228103 ,3 ,209 );

        
insert into db_syscampo values(1010359,'c131_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1010360,'c131_db_itensmenu','int4','Item de menu gerado','0', 'Código do Item de Menu',10,'f','f','f',1,'text','Código do Item de Menu');
insert into db_syscampo values(1010361,'c131_nome','varchar(100)','Nome do Relatório','', 'Nome do Relatório',100,'f','t','f',0,'text','Nome do Relatório');
insert into db_syscampo values(1010362,'c131_filtros','text','JSON com os filtros','', 'Filtros',1,'f','t','f',0,'text','Filtros');
insert into db_sysarquivo values (1010425, 'visaocontacorrente', 'visaocontacorrente', 'c131', '2019-02-28', 'visaocontacorrente', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,1010425);
insert into db_sysarqcamp values(1010425,1010359,1,0);
insert into db_sysarqcamp values(1010425,1010360,2,0);
insert into db_sysarqcamp values(1010425,1010361,3,0);
insert into db_sysarqcamp values(1010425,1010362,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010425,1010359,1,1010361);
insert into db_syssequencia values(1000821, 'visaocontacorrente_c131_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000821 where codarq = 1010425 and codcam = 1010359;
insert into db_sysindices values(1008434,'visaocontacorrente_db_itensmenu_in',1010425,'0');
insert into db_syscadind values(1008434,1010360,1);
insert into db_sysforkey values(1010425,1010360,1,156,0);


create table contabilidade.visaocontacorrente (
    c131_sequencial serial not null primary key,
    c131_db_itensmenu integer not null,
    c131_nome varchar(100) not null,
    c131_filtros text not null
);

alter table visaocontacorrente add constraint visaocontacorrente_db_itensmenu_fk foreign key (c131_db_itensmenu) references db_itensmenu;
create index visaocontacorrente_db_itensmenu_in on visaocontacorrente(c131_db_itensmenu);


SQL_UP
);
    }


    public function down()
    {


        $this->execute(<<<SQL_DOWN


delete from db_menu where id_item_filho = 228103 AND modulo = 209;
delete from db_itensmenu where id_item = 228103;
delete from db_sysforkey where codarq = 1010425;
delete from db_syscadind where codcam in (1010360);
delete from db_sysindices where codarq = 1010425;
delete from db_syssequencia where codsequencia = 1000821;
delete from db_sysprikey where codarq = 1010425;
delete from db_sysarqcamp where codarq = 1010425;
delete from db_sysarqmod where codarq = 1010425;
delete from db_sysarquivo where codarq = 1010425;
delete from db_syscampo where codcam in (1010359, 1010360, 1010361, 1010362);

drop table visaocontacorrente;


SQL_DOWN
);
    }
}
