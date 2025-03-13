<?php

use Classes\PostgresMigration;

class M19065MenuAcertaAcordoItemDotacao extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO db_itensmenu
                (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            SELECT
                nextval('db_itensmenu_id_item_seq'),
                'Acerta Valores Item Acordo Dotação',
                'Acerta Valores Item Acordo Dotação',
                'aco4_acertaacordoitemdotacao001.php',
                1,
                1,
                'Acerta Valores Item Acordo Dotação',
                't'
            WHERE
                NOT EXISTS(
                    SELECT id_item FROM db_itensmenu WHERE descricao = 'Acerta Valores Item Acordo Dotação'
                );
            
            INSERT INTO db_menu VALUES (32, currval('db_itensmenu_id_item_seq'), (select max(menusequencia) + 1 from db_menu), 8251);
        ");
    }

    public function down()
    {
        $this->execute("DELETE FROM db_itensmenu WHERE descricao = 'Acerta Valores Item Acordo Dotação'");
        $this->execute("
            DELETE FROM db_menu WHERE id_item_filho IN(
                SELECT
                    id_item
                FROM
                    db_itensmenu
                WHERE
                    descricao = 'Acerta Valores Item Acordo Dotação'
            )
        ");
    }
}