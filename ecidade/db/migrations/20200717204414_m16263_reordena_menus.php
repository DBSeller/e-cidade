<?php

use Classes\PostgresMigration;

class M16263ReordenaMenus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
            values ( 228299 ,'Anexo 13 - A' ,'Anexo 13 - A' ,'con2_tceroanexosin22011.php?anexo=13a' ,'1' ,'1' ,'TCE/RO - Anexo 13 A' ,'true' );

            delete from db_menu where id_item_filho in (228294, 228295, 228296, 228297, 228298, 228299) AND modulo = 209;

            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
            values ( 228293 ,228294 ,1 ,209 ),
                   ( 228293 ,228295 ,2 ,209 ),
                   (228293, 228299, 3, 209),
                   ( 228293 ,228296 ,4 ,209 ),
                   ( 228293 ,228297 ,5 ,209 ),
                   ( 228293 ,228298 ,6 ,209 );
SQL
        );

        $this->execute(<<<SQL
            insert into orcparamrel values (232, 'TCE/RO - ANEXO 13 A', 4, null);

            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 17, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 18, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 19, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 20, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 21, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 22, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 23, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 24, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 25, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 26, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 27, 232);
            insert into orcparamrelperiodos values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 28, 232);
SQL
);

    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho in (228294, 228295, 228296, 228297, 228298, 228299) AND modulo = 209;

            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
            values ( 228293 ,228294 ,1 ,209 ),
                   ( 228293 ,228295 ,2 ,209 ),
                   ( 228293 ,228296 ,3 ,209 ),
                   ( 228293 ,228297 ,4 ,209 ),
                   ( 228293 ,228298 ,5 ,209 );
            delete from db_itensmenu where id_item = 228299
SQL
        );

        $this->execute(<<< SQL
        delete from orcparamrelperiodos where o113_orcparamrel = 232;
        delete from orcparamrel where o42_codparrel = 232;
SQL
        );
    }
}
