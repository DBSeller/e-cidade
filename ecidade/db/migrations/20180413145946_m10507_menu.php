<?php

use Classes\PostgresMigration;

/**
 * Class M10507Menu
 *
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class M10507Menu extends PostgresMigration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $sql = <<<SQL
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) VALUES
                (10518, 'Desmembramento de Inicial do Foro', 'Desmembramento de Inicial do Foro',
                 'jur4_desmembramento_inicial001.php', '1', '1', 'Desmembramento de Inicial do Foro', 'false');
            DELETE FROM db_menu
            WHERE id_item_filho = 10518 AND modulo = 313;
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (1818, 10518, 117, 313);
SQL;

        $this->execute($sql);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $sql = <<<SQL
            DELETE FROM db_itensmenu WHERE id_item = 10518;
            DELETE FROM db_menu
            WHERE id_item_filho = 10518 AND modulo = 313;
SQL;

        $this->execute($sql);
    }
}
