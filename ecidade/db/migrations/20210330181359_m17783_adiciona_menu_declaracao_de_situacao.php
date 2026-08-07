<?php

use Classes\PostgresMigration;

class M17783AdicionaMenuDeclaracaoDeSituacao extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            values (228477, 'Declaração de Situação', 'Declaração de Situação', 'edu2_declaracaoanosatuais001.php',
                '1', '1','Declaração referente aos alunos somente do ano vigente', 'true');

            delete from db_menu
            where id_item_filho = 228477 AND modulo = 1100747;

            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
            values ( 1101109 ,228477 ,25 ,1100747 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu
            where id_item_filho = 228477;

            delete from db_itensmenu
            where id_item = 228477;
SQL
        );
    }
}
