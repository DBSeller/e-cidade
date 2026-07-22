<?php

use Classes\PostgresMigration;

class M17692AdicionaMenuAlunosAtendidosAee extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente )
        values ( 228401 ,
        'Alunos atendidos AEE' ,
        'Alunos atendidos AEE' ,
        'edu2_alunonecessidadegeral001.php' ,
        '1' ,
        '1' ,
        'Alunos Atendidos AEE - Relatório de todos os alunos Atendidos aee possuindo necessidades especiais ou não.'
        ,'false' );
        delete from db_menu where id_item_filho = 228401 AND modulo = 7159;
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo )
        values ( 1101109 ,228401 ,24 ,7159 );
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
        delete from db_menu where id_item_filho = 228401;
        delete from db_itensmenu where id_item = 228401;
sql
        );
    }
}
