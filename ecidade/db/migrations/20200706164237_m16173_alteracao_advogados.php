<?php

use Classes\PostgresMigration;

class M16173AlteracaoAdvogados extends PostgresMigration
{
    public function up()
        {
            $this->execute(<<<SQL_UP

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228270 ,'Alteração de Advogados por Lista' ,'Alteração de Advogados por Lista' ,'jur4_alteraradvog001.php' ,'1' ,'1' ,'Alteração de Advogados por Lista' ,'true' );
delete from db_menu where id_item_filho = 228270 AND modulo = 313;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1789 ,228270 ,6 ,313 );

SQL_UP
    );
        }

        public function down()
        {
            $this->execute(<<<SQL_DOWN

delete from db_menu where id_item_filho = 228261;
delete from db_itensmenu where id_item = 1789;

SQL_DOWN
            );
        }

}
