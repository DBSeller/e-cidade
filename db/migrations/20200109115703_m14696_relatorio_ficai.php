<?php

use Classes\PostgresMigration;

class M14696RelatorioFicai extends PostgresMigration
{

    public function up()
    {
        $this->execute("
            insert into db_itensmenu values (228199, 'Aluno Infrequente - FICAI', 'Aluno Infrequente - FICAI', 'edu2_ficai001.php', 1, 1, 'Relatório para emitir a ficha FICAI', True);
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1101109 ,228199 ,19 ,1100747 );
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_menu where id_item =1101109 and id_item_filho = 228199;
            delete from db_itensmenu where id_item = 228199;
        ");
    }
}
