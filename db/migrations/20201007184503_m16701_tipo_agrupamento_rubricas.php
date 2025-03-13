<?php

use Classes\PostgresMigration;

class M16701TipoAgrupamentoRubricas extends PostgresMigration
{
  
    public function up()
    {
        $sql = "
        create table pessoal.tipoagrupamentorubrica (
            rh238_sequencial serial primary key,
            rh238_descricao varchar(100)
        );
        alter table
            pessoal.agrupamentorubrica
        ADD
            COLUMN rh113_tipogrupo integer;

        alter table
            pessoal.agrupamentorubrica
        ADD
            CONSTRAINT tipoagrupamentorubrica_agrupamentorubrica_fk FOREIGN KEY (rh113_tipogrupo) REFERENCES pessoal.tipoagrupamentorubrica(rh238_sequencial);

        insert into
            tipoagrupamentorubrica
        values
            (1, 'Termo de Rescisão');

        insert into
            tipoagrupamentorubrica
        values
            (2, 'BI');

        select
            setval('tipoagrupamentorubrica_rh238_sequencial_seq', 2);

        update
            pessoal.agrupamentorubrica
        set
            rh113_tipogrupo = 1;

        insert into db_sysarquivo values (1010624, 'tipoagrupamentorubrica', 'Cadastro de Tipos de Agrupamento de Rubricas', 'rh238', '2020-10-07', 'Tipos de Agrupamento de Rubricas', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (28,1010624);
        insert into db_syscampo values(1011847,'rh238_sequencial','int4','Sequencial ta tabela tipoagrupamentorubrica','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        insert into db_syscampo values(1011848,'rh238_descricao','varchar(100)','Descrição do tipo de agrupamento.','', 'Descrição',100,'f','t','f',0,'text','Descrição');
        insert into db_sysarqcamp values(1010624,1011847,1,0);
        insert into db_sysarqcamp values(1010624,1011848,2,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010624,1011847,1,1011847);
        insert into db_syssequencia values(1000974, 'tipoagrupamentorubrica_rh238_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000974 where codarq = 1010624 and codcam = 1011847;
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228333 ,'Tipo de Agrupamento de Rubricas' ,'Tipo de Agrupamento de Rubricas' ,'' ,'1' ,'1' ,'Cadastro de Tipo de Agrupamento de Rubricas' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 29 ,228333 ,296 ,952 );
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228334 ,'Tipo Agrupamento de Rubrica' ,'Tipo Agrupamento de Rubrica' ,'pes1_tipoagrupamentorubrica001.php?opcao=1' ,'1' ,'1' ,'Inclusão de Tipo Agrupamento de Rubrica' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228333 ,228334 ,1 ,952 );
        update db_itensmenu set id_item = 228334 , descricao = 'Inclusão' , help = 'Inclusão' , funcao = 'pes1_tipoagrupamentorubrica001.php?opcao=1' , itemativo = '1' , manutencao = '1' , desctec = 'Inclusão de Tipo Agrupamento de Rubrica' , libcliente = 'true' where id_item = 228334;
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228335 ,'Alteração' ,'Alteração' ,'pes1_tipoagrupamentorubrica001.php?opcao=2' ,'1' ,'1' ,'Alteração de tipo de agrupamento de rubrica' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228333 ,228335 ,2 ,952 );
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228336 ,'Exclusão' ,'Exclusão' ,'pes1_tipoagrupamentorubrica001.php?opcao=3' ,'1' ,'1' ,'Exclusão de Tipo Agrupamento de Rubrica' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228333 ,228336 ,3 ,952 );
        
        insert into db_syscampo values(1011850,'rh113_tipogrupo','int4','Tipo Grupo de Agrupamento de Rubrica','0', 'Tipo Grupo',10,'f','f','f',1,'text','Tipo Grupo');
        insert into db_sysarqcamp values(3478,1011850,5,0);
        insert into db_sysforkey values(3478,1011850,1,1010624,0);
        
        ";

        $this->execute($sql);
    }
  
    public function down()
    {
        $sql = "
        delete from db_sysarquivo where codarq = 1010624;
        delete from db_sysarqcamp where codarq = 1010624;
        delete from db_sysarqcamp where codcam = 1011850;
        delete from db_sysforkey where codcam = 1011850;
        delete from db_sysarqmod where codarq = 1010624;
        delete from db_syscampo where codcam in (1011847,1011848,1011850);
        delete from db_sysprikey where codarq = 1010624;
        delete from db_syssequencia where codsequencia = 1000974;
        delete from db_menu where id_item_filho in (228333,228334,228335,228336);
        delete from db_itensmenu where id_item in (228333,228334,228335,228336);
        alter table agrupamentorubrica drop column rh113_tipogrupo ;
        drop table pessoal.tipoagrupamentorubrica;
        ";
        $this->execute($sql);
    }
}
