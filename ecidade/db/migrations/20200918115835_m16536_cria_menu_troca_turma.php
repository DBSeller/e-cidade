<?php

use Classes\PostgresMigration;

class M16536CriaMenuTrocaTurma extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            update db_itensmenu set
                id_item = 9613,
                descricao = 'Por Aluno',
                help = 'Por Aluno',
                funcao = 'edu1_alunotransfturma001.php',
                itemativo = '1',
                manutencao = '1',
                desctec = 'Realiza a troca de turma do aluno.',
                libcliente = 'true'
                    where id_item = 9613;

            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
                values (228326, 'Por Turma', 'Por Turma', 'edu4_trocaporturma.php', '1', '1',
                        'Realiza a troca de alunos por turma em lote','true');
            delete from db_menu where id_item_filho = 228326 AND modulo = 1100747;
            insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (9631, 228326, 3, 1100747);

            update db_menu set menusequencia = 1 where id_item = 9631 and modulo = 1100747 and id_item_filho = 9613;
            update db_menu set menusequencia = 2 where id_item = 9631 and modulo = 1100747 and id_item_filho = 228326;
            update db_menu set menusequencia = 3 where id_item = 9631 and modulo = 1100747 and id_item_filho = 9614;
SQL
        );
    }
    public function down()
    {
        $this->execute(<<<SQL
            update db_itensmenu set
                id_item = 9613,
                descricao = 'Trocar',
                help = 'Trocar',
                funcao = 'edu1_alunotransfturma001.php',
                itemativo = '1',
                manutencao = '1',
                desctec = 'Realiza a troca de turma do aluno.',
                libcliente = 'true'
                    where id_item = 9613;

            delete from db_menu where id_item_filho = 228326 AND modulo = 1100747;
            delete from db_itensmenu where id_item = 228326;

            update db_menu set menusequencia = 1 where id_item = 9631 and modulo = 1100747 and id_item_filho = 9613;
            update db_menu set menusequencia = 2 where id_item = 9631 and modulo = 1100747 and id_item_filho = 9614;
SQL
        );
    }
}
