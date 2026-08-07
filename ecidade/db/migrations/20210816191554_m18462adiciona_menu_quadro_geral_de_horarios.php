<?php

use Classes\PostgresMigration;

class M18462adicionaMenuQuadroGeralDeHorarios extends PostgresMigration
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
                    228555,
                    'Quadro Geral de Horários',
                    'Quadro Geral de Horários',
                    'edu3_quadrogeraldehorarios001.php',
                    '1',
                    '1',
                    'Quadro Geral de Horários',
                    'true'
                );

            DELETE FROM
                db_menu
            WHERE
                id_item_filho = 228555
                AND modulo = 7159;

            INSERT INTO
                db_menu(id_item, id_item_filho, menusequencia, modulo)
            VALUES
                (3333, 228555, 24, 7159);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228555;
            delete from db_itensmenu where id_item = 228555;
SQL
        );
    }
}
