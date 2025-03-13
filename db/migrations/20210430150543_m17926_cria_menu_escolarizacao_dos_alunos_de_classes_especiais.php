<?php

use Classes\PostgresMigration;

class M17926CriaMenuEscolarizacaoDosAlunosDeClassesEspeciais extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            values (228496, 'Escolarização dos Alunos de Classes Especiais', 'Escolarização dos Alunos de Classes Especiais',
            'edu2_alunoespecialquantiquali001.php', '1', '1', 'Relatório de escolarização nominal e quantitativo', 'false');

            delete
            from db_menu
            where id_item_filho = 228496
            AND modulo = 1100747;

            insert into db_menu(id_item, id_item_filho, menusequencia, modulo)
            values (1101110, 228496, 39, 1100747);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228496;
            delete from db_itensmenu where id_item = 228496;
SQL
        );
    }
}
