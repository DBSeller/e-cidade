<?php

use Classes\PostgresMigration;

class M11571CriandoMenuPermissaoVagasTurmas extends PostgresMigration
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
                228385,
                'Libera o Campo Vagas em Turmas',
                'Libera o campo "Vagas:" no Cadastro de Turmas.',
                '',
                '1',
                '1',
                'Libera o campo "Vagas:" na tela de Cadastro de Turmas.',
                'true'
            );

            DELETE FROM
                db_menu
            WHERE
                id_item_filho = 228385
                AND modulo = 1100747;

            INSERT INTO
                db_menu(id_item, id_item_filho, menusequencia, modulo)
            VALUES
                (1100873, 228385, 9, 1100747);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            DELETE FROM
                db_menu
            WHERE
                id_item_filho = 228385;

            DELETE FROM
                db_itensmenu
            WHERE
                id_item = 228385;
SQL
        );
    }
}
