<?php

use Classes\PostgresMigration;

class M15300CriacaoDosMenusRelatorios extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228223 ,'TCE/RO - Anexos IN 22' ,'Anexos legais para prestação de contas da IN 22.' ,'' ,'1' ,'1' ,'Anexos legais para prestação de contas da IN 22.' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3331 ,228223 ,53 ,209 );

insert into db_itensmenu values (228224, 'Anexo 1', 'TCE/RO - Anexo 1', 'con2_tceroanexosin22011.php?anexo=1&relatorio=206', 1, '1', 'TCE/RO - Anexo 1', true);
insert into db_itensmenu values (228225, 'Anexo 2', 'TCE/RO - Anexo 2', 'con2_tceroanexosin22011.php?anexo=2', 1, '1', 'TCE/RO - Anexo 2', true);
insert into db_itensmenu values (228226, 'Anexo 3', 'TCE/RO - Anexo 3', 'con2_tceroanexosin22011.php?anexo=3', 1, '1', 'TCE/RO - Anexo 3', true);
insert into db_itensmenu values (228227, 'Anexo 4', 'TCE/RO - Anexo 4', 'con2_tceroanexosin22011.php?anexo=4', 1, '1', 'TCE/RO - Anexo 4', true);
insert into db_itensmenu values (228228, 'Anexo 5', 'TCE/RO - Anexo 5', 'con2_tceroanexosin22011.php?anexo=5', 1, '1', 'TCE/RO - Anexo 5', true);
insert into db_itensmenu values (228229, 'Anexo 6', 'TCE/RO - Anexo 6', 'con2_tceroanexosin22011.php?anexo=6', 1, '1', 'TCE/RO - Anexo 6', true);
insert into db_itensmenu values (228230, 'Anexo 7', 'TCE/RO - Anexo 7', 'con2_tceroanexosin22011.php?anexo=7', 1, '1', 'TCE/RO - Anexo 7', true);
insert into db_itensmenu values (228231, 'Anexo 8', 'TCE/RO - Anexo 8', 'con2_tceroanexosin22011.php?anexo=8', 1, '1', 'TCE/RO - Anexo 8', true);
insert into db_itensmenu values (228232, 'Anexo 9', 'TCE/RO - Anexo 9', 'con2_tceroanexosin22011.php?anexo=9', 1, '1', 'TCE/RO - Anexo 9', true);
insert into db_itensmenu values (228233, 'Anexo 10', 'TCE/RO - Anexo 10', 'con2_tceroanexosin22011.php?anexo=10', 1, '1', 'TCE/RO - Anexo 10', true);


insert into db_menu values (228223, 228224, 1, 209);
insert into db_menu values (228223, 228225, 2, 209);
insert into db_menu values (228223, 228226, 3, 209);
insert into db_menu values (228223, 228227, 4, 209);
insert into db_menu values (228223, 228228, 5, 209);
insert into db_menu values (228223, 228229, 6, 209);
insert into db_menu values (228223, 228230, 7, 209);
insert into db_menu values (228223, 228231, 8, 209);
insert into db_menu values (228223, 228232, 9, 209);
insert into db_menu values (228223, 228233, 10, 209);

SQL_UP
);
    }


    public function down()
    {
        $this->execute(<<<SQL_DOWN


delete from db_menu where id_item_filho in (228224,228225,228226,228227,228228,228229,228230,228231,228232,228233,228223);
delete from db_itensmenu where id_item in (228224,228225,228226,228227,228228,228229,228230,228231,228232,228233,228223);


SQL_DOWN
);
    }

}
