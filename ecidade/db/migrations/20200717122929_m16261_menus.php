<?php

use Classes\PostgresMigration;

class M16261Menus extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
        values ( 228292 ,'Manutenção e Desenvolvimento do Ensino' ,'Manutenção e Desenvolvimento do Ensino' ,'' ,'1' ,'1' ,'Manutenção e Desenvolvimento do Ensino' ,'true' ),
               ( 228293 ,'Ações e Serviços Publicos de Saúde' ,'Ações e Serviços Publicos de Saúde' ,'' ,'1' ,'1' ,'Ações e Serviços Publicos de Saúde' ,'true' ),
               ( 228294 ,'Anexo 12' ,'Anexo 12' ,'con2_tceroanexosin22011.php?anexo=12' ,'1' ,'1' ,'TCE/RO - Anexo 12' ,'true' ),
               ( 228295 ,'Anexo 13' ,'Anexo 13' ,'con2_tceroanexosin22011.php?anexo=13' ,'1' ,'1' ,'TCE/RO - Anexo 13' ,'true' ),
               ( 228296 ,'Anexo 14' ,'Anexo 14' ,'con2_tceroanexosin22011.php?anexo=14' ,'1' ,'1' ,'TCE/RO - Anexo 14' ,'true' ),
               ( 228297 ,'Anexo 15' ,'Anexo 15' ,'con2_tceroanexosin22011.php?anexo=15' ,'1' ,'1' ,'TCE/RO - Anexo 15' ,'true' ),
               ( 228298 ,'Anexo 16' ,'Anexo 16' ,'con2_tceroanexosin22011.php?anexo=16' ,'1' ,'1' ,'TCE/RO - Anexo 16' ,'true' );

        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
        values ( 228223 ,228292 ,11 ,209 ),
               ( 228223 ,228293 ,12 ,209 );

        delete from db_menu where id_item in(228293, 228292);
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
        values (228293 ,228294 ,1 ,209),
               (228293 ,228295 ,2 ,209),
               (228293, 228299, 3, 209),
               (228293 ,228296 ,4 ,209),
               (228293 ,228297 ,5 ,209),
               (228293 ,228298 ,6 ,209);

        delete from db_menu where id_item_filho in (228224, 228225, 228226, 228227, 228228, 228229, 228230, 228231, 228232, 228233) AND modulo = 209;

        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
        values (228292 ,228224 ,1 ,209),
               (228292 ,228225 ,2 ,209),
               (228292 ,228226 ,3 ,209),
               (228292 ,228227 ,4 ,209),
               (228292 ,228228 ,5 ,209),
               (228292 ,228229 ,6 ,209),
               (228292 ,228230 ,7 ,209),
               (228292 ,228231 ,8 ,209),
               (228292 ,228232 ,9 ,209),
               (228292 ,228233 ,10 ,209);
SQL
        );

    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho in (228224, 228225, 228226, 228227, 228228, 228229, 228230, 228231, 228232, 228233) AND modulo = 209;
            delete from db_menu where id_item_filho in (228292, 228293, 228294, 228295, 228296, 228297, 228298) AND modulo = 209;

            insert into db_menu
            values (228223, 228224, 1, 209),
                   (228223, 228225, 2, 209),
                   (228223, 228226, 3, 209),
                   (228223, 228227, 4, 209),
                   (228223, 228228, 5, 209),
                   (228223, 228229, 6, 209),
                   (228223, 228230, 7, 209),
                   (228223, 228231, 8, 209),
                   (228223, 228232, 9, 209),
                   (228223, 228233, 10, 209);

            delete from db_itensmenu where id_item in (228292, 228293, 228294, 228295, 228296, 228297, 228298);
SQL
        );
    }
}
