<?php

use Classes\PostgresMigration;

class M13760AlteracaoContaLancamentos extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228144 ,'Alteração de Contas de Lançamento' ,'Altera as contas de um lançamento contábil.' ,'con4_alterarcontaslancamento001.php' ,'1' ,'1' ,'Alteração de Contas de Lançamento' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4197 ,228144 ,16 ,209 );


SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_UP
        
delete from db_menu where id_item_filho = 228144 AND modulo = 209;
delete from db_itensmenu where id_item = 228144;

SQL_UP
);
    }
}
