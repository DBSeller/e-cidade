<?php

use Classes\PostgresMigration;

class M12227CaracteristicaPeculiar extends PostgresMigration
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

        $this->execute("insert into concarpeculiarclassificacao values (4, 'Operações de Crédito')");
        $this->execute("insert into concarpeculiarclassificacao values (5, 'Convênios')");
        $this->execute("update db_syscampo set rotulo = 'Classificação', rotulorel = 'Classificação'  where nomecam = 'c58_tipo'");
    }

    public function down()
    {

        $this->execute("delete from concarpeculiarclassificacao where c09_sequencial in (4,5);");
    }
}
