<?php

use Classes\PostgresMigration;

class M14012AlteracaoConplanoorcamento extends PostgresMigration
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
        $this->execute("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228155 ,'Alteração em Lote' ,'Alteração em Lote' ,'orc4_alteracaoloteconplanoorcamento001.php' ,'1' ,'1' ,'Alteração em Lote' ,'true' )");
        $this->execute("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9072 ,228155 ,8 ,209 );");
    }

    public function down()
    {
        $this->execute("delete from db_menu where id_item_filho = 228155 AND modulo = 209;");
        $this->execute("delete from deb_itensmenu where id_item = 228155");
    }
}
