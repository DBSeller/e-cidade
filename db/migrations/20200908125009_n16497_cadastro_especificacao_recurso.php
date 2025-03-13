<?php

use Classes\PostgresMigration;

class N16497CadastroEspecificacaoRecurso extends PostgresMigration
{
    public function up()
    {
        $this->execute("
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228325 ,'Cadastro de Especificação' ,'Cadastro de Especificação' ,'orc1_especificacaorecurso001.php' ,'1' ,'1' ,'Cadastro de Especificação' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3176 ,228325 ,5 ,116 );
        ");
    }

    public function down()
    {
        $this->execute("
        delete from db_menu where id_item_filho = 228325;
        delete from db_itensmenu where id_item = 228325;
        ");
    }
}
