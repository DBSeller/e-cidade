<?php

use Classes\PostgresMigration;

class M18043MenuRelatorioProgramasGestao extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228499 ,'Programas Estratégicos - Gestão PPA' ,'Programas Estratégicos - Gestão PPA' ,'pla2_programas_gestao.php' ,'1' ,'1' ,'Emite os Programas de Gestão PPA' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228497 ,228499 ,2 ,228358 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228499;
delete from db_itensmenu where id_item = 228499;
SQL
        );
    }
}
