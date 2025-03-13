<?php

use Classes\PostgresMigration;

class Bug11311SchemaTabelaConsistencia extends PostgresMigration
{

    public function up()
    {

        $row = (object)$this->fetchRow("select * from pg_tables where tablename = 'consistenciasistema';");
        if ($row->schemaname != 'configuracoes') {
            $this->execute('alter table consistenciasistema set schema configuracoes;');
        }
    }

    public function down()
    {

    }
}
