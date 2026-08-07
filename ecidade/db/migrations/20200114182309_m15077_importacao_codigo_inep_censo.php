<?php

use Classes\PostgresMigration;

class M15077ImportacaoCodigoInepCenso extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            update db_itensmenu set libcliente = 'false' where id_item = 8122;
            update db_itensmenu set descricao = 'Código INEP / Matrícula INEP Aluno' , help = 'Código INEP / Matrícula INEP Aluno', desctec = 'Rotina de importação dos códigos de inep e da matricula do aluno' where id_item = 9891;
            insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (8993, 9891, 2, 7159);
        ");
    }

    public function down()
    {
        $this->execute("
            update db_itensmenu set libcliente = 'true' where id_item = 8122;
            update db_itensmenu set descricao = 'Importar Situação do Aluno' , help = 'Importação da situação do aluno', desctec = 'Rotina de importação da situação do aluno para o censo.' where id_item = 9891;
            delete from db_menu where id_item_filho = 9891 AND modulo = 7159;
        ");
    }
}
