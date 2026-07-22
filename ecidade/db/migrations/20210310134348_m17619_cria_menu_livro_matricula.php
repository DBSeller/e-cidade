<?php

use Classes\PostgresMigration;

class M17619CriaMenuLivroMatricula extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            INSERT INTO
                db_itensmenu(
                    id_item,
                    descricao,
                    help,
                    funcao,
                    itemativo,
                    manutencao,
                    desctec,
                    libcliente
            )
            VALUES
            (
                228389,
                'Livro de Matrícula',
                'Livro de Matrícula',
                'edu2_livromatricula001.php',
                '1',
                '1',
                'RELATÓRIO DE LIVRO DE MATRÍCULA
            Fonte: edu2_livromatricula001.php',
                'true'
            );

            DELETE FROM
                db_menu
            WHERE
                id_item_filho = 228389
                AND modulo = 1100747;

            INSERT INTO
                db_menu(id_item, id_item_filho, menusequencia, modulo)
            VALUES
                (1101112, 228389, 16, 1100747);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            DELETE FROM
                db_menu
            WHERE
                id_item_filho = 228389;

            DELETE FROM
                db_itensmenu
            WHERE
                id_item = 228389;
SQL
        );
    }
}
