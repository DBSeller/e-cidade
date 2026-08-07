<?php

use Classes\PostgresMigration;

class M12276MenuAndamentoProcesso extends PostgresMigration
{
    public function up()
    {
        $sql = '
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228068 ,\'Andamento do processo\' ,\'Andamento do processo\' ,\'pro4_andamento_processo.php\' ,\'1\' ,\'1\' ,\'Tela para receber, despachar e transferir o processo.\' ,\'false\' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228068 ,504 ,604 );
        ';
        $this->execute($sql);
    }

    public function down()
    {
        $sql = '
            delete from db_menu where id_item_filho = 228068 AND modulo = 604;
            delete from db_itensmenu where id_item = 228068;
        ';
        $this->execute($sql);
    }
}
