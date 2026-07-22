<?php

use Classes\PostgresMigration;

class M17458AdicionaMenusQuadroDeVagaseQuadroDeVagasPorBairros extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            values (228439, 'Quadro de Vagas Geral da Rede', 'Exibir vagas disponíveis em todas as escolas',
            'edu2_QuadroDeVagas001.php', '1', '1', 'Exibir vagas disponíveis em todas as escolas', 'true');

            delete
            from db_menu
            where id_item_filho = 228439
            AND modulo = 7159;

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
            values (1101112, 228439, 18, 7159);

            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            values (228440, 'Quadro de Vagas Geral por Bairros', 'Quadro de Vagas Geral por Bairros',
            'edu2_quadrodevagasbairro001.php', '1', '1', 'edu2_quadrodevagasbairro001.php', 'true');

            delete
            from db_menu
            where id_item_filho = 228440
            AND modulo = 7159;

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
            values (1101112, 228440, 19, 7159);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228439;
            delete from db_itensmenu where id_item = 228439;

            delete from db_menu where id_item_filho = 228440;
            delete from db_itensmenu where id_item = 228440;
SQL
        );
    }
}
