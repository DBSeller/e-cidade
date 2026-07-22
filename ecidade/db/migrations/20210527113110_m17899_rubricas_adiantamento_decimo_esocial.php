<?php

use Classes\PostgresMigration;

class M17899RubricasAdiantamentoDecimoEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = "
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228483 ,'Rubricas de Adiantamento de 13º' ,'Rubricas de Adiantamento de 13º' ,'eso01_cargarubricasadiantamento13.php' ,'1' ,'1' ,'Geração das Rubricas de adiantamento de 13º' ,'true' );
            delete from db_menu where id_item_filho = 228483 AND modulo = 10216;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10425 ,228483 ,3 ,10216 );

        ";
        $this->execute($sql);
    }
    public function down()
    {
        $sql = "
        delete from db_menu where id_item_filho = 228483 AND modulo = 10216;
        delete from db_itensmenu where id_item = 228483;
        ";
        $this->execute($sql);
    }
}
