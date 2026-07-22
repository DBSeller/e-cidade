<?php

use Classes\PostgresMigration;

class M17617CriaMenuDeclaracaoExAlunos extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (
                        228387,
                        'Declaração de ex-alunos',
                        'Declaração de ex-alunos',
                        'edu2_declaracaoexalunos001.php',
                        '1',
                        '1',
                        'Declaração para alunos que não estejam matriculados no ano vigente daquela escola',
                        'true'
                        );
            delete from db_menu where id_item_filho = 228387 AND modulo = 1100747;

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (1101109, 228387, 21, 1100747);
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            delete from db_menu where id_item_filho = 228387;
            delete from db_itensmenu where id_item = 228387;
sql
        );
    }
}
