<?php

use Classes\PostgresMigration;

class M15183RelatorioDebitoSuspenso extends PostgresMigration
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

        $this->createDicionarioDados();
    }

    public function down()
    {

        $this->dropDicionarioDados();
    }

    private function createDicionarioDados()
    {

        $sql = <<<SQL
               insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228202 ,'Débitos Suspensos' ,'Débitos Suspensos' ,'arr2_debitosuspenso001.php' ,'1' ,'1' ,'Relatório de débitos suspensos' ,'true' );
               insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,228202 ,478 ,1985522 );
SQL;
        $this->execute($sql);
    }

    private function dropDicionarioDados()
    {

        $sql = <<<SQL
        delete from db_menu where id_item_filho = 228202 AND modulo = 1985522;
        delete from db_itensmenu where id_item = 228202;

SQL;
        $this->execute($sql);
    }

}
