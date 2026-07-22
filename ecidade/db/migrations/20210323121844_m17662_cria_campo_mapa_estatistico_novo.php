<?php

use Classes\PostgresMigration;

class M17662CriaCampoMapaEstatisticoNovo extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
        values ( 228436 ,'Mapa Estatístico por Calendário (Novo)' ,'Mapa Estatístico por Calendário (Novo)' ,'edu2_mapaestatisticocal001.php' ,'1' ,'1' ,'RELATÓRIO DE MAPA ESTATÍSTICO POR CALENDÁRIO' ,'true' );
        delete from db_menu where id_item_filho = 228436 AND modulo = 7159;
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1101112 ,228436 ,17 ,7159 );
sql
        );

    }

    public function down()
    {
        $this->execute(<<<sql
        delete from db_menu where id_item_filho = 228436;
        delete from db_itensmenu where id_item = 228436;
sql
        );
    }
}
