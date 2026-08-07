<?php

use Classes\PostgresMigration;

class M16559CriaImpmodeloGuiaItbi extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */

    public function up()
    {

        $sSql = <<<SQL

            insert into cadmodcarne values(106,'GUIA DE ITBI COBRANCA MOD 3','',0,0,'',2);
            select setval('cadmodcarne_k47_sequencial_seq', (select max(k47_sequencial) from cadmodcarne));

SQL;
        $this->execute($sSql);
    }

    public function down()
    {

        $sSql = <<<SQL
          
            delete from cadmodcarne where k47_sequencial = 106;
            select setval('cadmodcarne_k47_sequencial_seq', (select max(k47_sequencial) from cadmodcarne));

SQL;
        $this->execute($sSql);
    }

}
