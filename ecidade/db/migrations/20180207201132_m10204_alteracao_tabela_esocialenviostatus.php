<?php

use Classes\PostgresMigration;

class M10204AlteracaoTabelaEsocialenviostatus extends PostgresMigration
{
    public function up()
    {
        $this->table('esocialenviostatus')
            ->changeColumn('rh214_descricao', 'text', array('null' => true, 'default' => null))
            ->update();
    }

    public function down()
    {
        $this->table('esocialenviostatus')
            ->changeColumn('rh214_descricao', 'string', array('limit' => 200, 'null' => true, 'default' => null))
            ->update();
    }
}
