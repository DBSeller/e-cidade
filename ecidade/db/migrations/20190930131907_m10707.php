<?php

use Classes\PostgresMigration;

class M10707 extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_itensmenu set descricao = 'Diário de Classe' where id_item = 9242");
    }

    public function down()
    {
        $this->execute("update db_itensmenu set descricao = 'Diário de Classe' where id_item = 9242");
    }
}
