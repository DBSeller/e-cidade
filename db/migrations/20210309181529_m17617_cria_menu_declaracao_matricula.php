<?php

use Classes\PostgresMigration;

class M17617CriaMenuDeclaracaoMatricula extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec ,libcliente)
                values (
                        228388,
                        'Declaração de Matrícula',
                        'Declaração de Matrícula',
                        'edu2_declaracaomatricula001.php',
                        '1',
                        '1',
                        'Declaração de Matrícula, antigo Atestado de Frequência, foi criado uma nova classe no sistema chamada edu2_declaracaomatricula001.php e permanece porem não utiliza-se a do atestado de vagas.',
                        'false'
                    );

            delete from db_menu where id_item_filho = 228388 AND modulo = 1100747;
            insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (1101109, 228388, 22, 1100747);
            delete from db_menu where id_item_filho = 228388 AND modulo = 7159;
            insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (1101109, 228388, 23, 7159);
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            delete from db_menu where id_item_filho = 228388;
            delete from db_itensmenu where id_item = 228388;
sql
        );
    }
}
