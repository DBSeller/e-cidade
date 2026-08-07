<?php

use Classes\PostgresMigration;

class M16263AtualizacaoMenu extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
         update db_itensmenu set descricao = 'Manutenção e Desenvolvimento do Ensino' where id_item = 228292;
         update db_itensmenu set descricao = 'Ações e Serviços Publicos de Saúde', libcliente = 'true' where id_item = 228293;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            update db_itensmenu set libcliente = 'false' where id_item = 228293;
SQL
        );

    }
}
