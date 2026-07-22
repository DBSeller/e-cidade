<?php

use Classes\PostgresMigration;

class M14438MapaColeta extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
            values ( 
                228200,
                'Mapa de Coleta',
                'Relatório Mapa de Coleta',
                'lab2_mapacoleta001.php',
                '1',
                '1',
                'Gerar mapa de coleta das unidades que realizam coleta de materiais e os ' ||
                 'enviam para o laboratório central.',
                'true'
            );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8171 ,228200 ,7 ,8167 );
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_menu where id_item_filho = 228200 AND modulo = 8167;
            delete from db_itensmenu where id_item = 228200;
        ");
    }
}
