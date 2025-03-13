<?php

use Classes\PostgresMigration;

class M17618CriaMenusRelatoriosTurmas extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            insert into db_itensmenu (id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (228390, 'Alunos Avaliados por Parecer', 'Alunos Avaliados por Parecer', 'edu2_alunoavaliadoparecer001.php', '1', '1', 'Alunos avaliados parecer', 'false'),
                       (228391, 'Nota Necessária Para Aprovação', 'Nota Necessária Para Aprovação', 'edu2_resultnotasanalitico001.php', '1', '1', 'Gerar Relatório com a quantidade de notas que falta para o aluno passar, assemelha-se ao relatório do conselho de classe', 'true' );

            delete from db_menu where id_item_filho = 228390 AND modulo = 1100747;
            delete from db_menu where id_item_filho = 228391 AND modulo = 1100747;

            insert into db_menu (id_item, id_item_filho, menusequencia, modulo)
                values (1101110, 228390, 37, 1100747),
                       (1101110, 228391, 38, 1100747);
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            delete from db_menu where id_item in (228390, 228391);
            delete from db_itensmenu where id_item in (228390, 228391);
sql
        );
    }
}
