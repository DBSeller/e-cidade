<?php

use Classes\PostgresMigration;

class M16297AnexoXa extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<< SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
values ( 228304 ,'Anexo 10 - A' ,'Anexo 10 - A' ,'con2_tceroanexosin22011.php?anexo=10a' ,'1' ,'1' ,'TCE/RO - Anexo 10 - A' ,'true' );
delete from db_menu where id_item_filho = 228304 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228292 ,228304 ,11 ,209 );
SQL
        );


        $this->execute(<<< SQL
        insert into orcparamrel values (235, 'TCE/RO - ANEXO 10 - A', 4, null);

        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 17, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 18, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 19, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 20, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 21, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 22, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 23, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 24, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 25, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 26, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 27, 235);
        insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 28, 235);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<< SQL
delete from db_menu where id_item_filho = 228304 AND modulo = 209;
delete from db_itensmenu where id_item = 228304;

delete from orcparamrelperiodos where o113_orcparamrel = 235;
delete from orcparamrel where o42_codparrel = 235;
SQL
        );
    }
}
