<?php

use Classes\PostgresMigration;

class M9191MenuConfiguracaoCustas extends PostgresMigration
{
    public function up()
    {
        $sql = "
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10487 ,'Vincular taxas / custas ao parcelamento' ,'Menu de cadastro dos vinculos de custas com a parcela desejada' ,'arr1_termotaxaparc001.php' ,'1' ,'1' ,'Vincula a taxa com a parcela' ,'true' );
            
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,10487 ,495 ,1985522 );
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_itensmenu where id_item = 10487;
            delete from db_menu where id_item_filho = 10487 AND modulo = 1985522;
        ";
        $this->execute($sql);
    }
}
