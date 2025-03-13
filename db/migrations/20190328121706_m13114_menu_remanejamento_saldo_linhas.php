<?php

use Classes\PostgresMigration;

class M13114MenuRemanejamentoSaldoLinhas extends PostgresMigration
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
       $this->execute("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228107 ,'Remanejamento de Linhas de Pacto' ,'Remanejamento de Linhas de Pacto' ,'orc4_remanejanentopplanolinhapaco001.php' ,'1' ,'1' ,'Remanejamento de Linhas de Pacto' ,'true' );");
       $this->execute("delete from db_menu where id_item_filho = 228107 AND modulo = 116;");
       $this->execute("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3215 ,228107 ,4 ,116 );");
    }


    public function down()
    {
        $this->execute("delete from db_menu where id_item_filho = 228107 AND modulo = 116;");
        $this->execute("delete from db_itens where id_item = 228107");
    }
}

