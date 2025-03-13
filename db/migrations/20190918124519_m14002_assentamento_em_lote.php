<?php

use Classes\PostgresMigration;

class M14002AssentamentoEmLote extends PostgresMigration
{

    public function up()
    {
        $sql = "
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228162 ,'Exclusão em Lote' ,'Excluí lotes de assentamentos' ,'rec4_excluir_assentamentos_lote.php' ,'1' ,'1' ,'A rotina excluí lotes de assentamentos.' ,'true' );
            delete from db_menu where id_item_filho = 228162 AND modulo = 2323;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10376 ,228162 ,6 ,2323 );
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228163 ,'Exclusão em Lote' ,'Excluí lotes de assentamentos' ,'rec4_excluir_assentamentos_lote.php?iTipoFuncionamento=1' ,'1' ,'1' ,'A rotina excluí lotes de assentamentos.' ,'true' );
            delete from db_menu where id_item_filho = 228163 AND modulo = 2323;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 5576 ,228163 ,5 ,2323 );
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            delete from db_menu where id_item_filho = 228162 AND modulo = 2323;
            delete from db_menu where id_item_filho = 228163 AND modulo = 2323;
            delete from db_itensmenu where id_item = 228162;
            delete from db_itensmenu where id_item = 228163;
        ";
        $this->execute($sql);

    }
}
