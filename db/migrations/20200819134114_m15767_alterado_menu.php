<?php

use Classes\PostgresMigration;

class M15767AlteradoMenu extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
        update db_itensmenu set descricao = 'Registro de Aula', help = 'Registro de Aula', desctec = 'Registro de Aula desenvolvido no dia de aula. E permite lançar as habilidades da BNCC' where id_item = 228234;
        update db_itensmenu set descricao = 'Registro de Aula', help = 'Registro de Aula', desctec = 'Registro de Aula' where id_item = 9633;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        update db_itensmenu set descricao = 'Lançamento de Conteúdo', help = 'Lançamento de Conteúdo', desctec = 'Lançamento de Conteúdo desenvolvido no dia de aula. E permite lançar as habilidades da BNCC' where id_item = 228234;
        update db_itensmenu set descricao = 'Conteúdo Desenvolvido' , help = 'Conteúdo Desenvolvido', desctec = 'Conteúdo Desenvolvido' where id_item = 9633;
SQL
        );
    }
}
