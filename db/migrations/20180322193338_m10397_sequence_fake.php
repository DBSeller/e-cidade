<?php

use Classes\PostgresMigration;

class M10397SequenceFake extends PostgresMigration
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
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
       $this->execute("
                     CREATE SEQUENCE cadastro.iptubase_j01_matric_schema_seq  START 1000000000;
       ");
    }

    public function down()
    {
        $this->execute("
                     DROP SEQUENCE cadastro.iptubase_j01_matric_schema_seq;
       ");
    }

}
