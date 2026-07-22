<?php

use Classes\PostgresMigration;

class M13822MenuAbertura extends PostgresMigration
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

        $this->execute(<<<SQL
insert into configuracoes.db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228185 ,'Abertura Contábil' ,'Abertura' ,'con4_aberturaexercicio.php' ,'1' ,'1' ,'Abertura do Exercício' ,'true' );
insert into configuracoes.db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9414 ,228185 ,5 ,209 );
update configuracoes.db_itensmenu set libcliente = false where id_item in(9491, 9492,10179);

SQL
);

    }
    public function down()
    {

        $this->execute("delete from configuracoes.db_menu where id_item_filho = 228185 AND modulo = 209;");
        $this->execute("delete from configuracoes.db_itensmenu where id_item = 228185");
        $this->execute("update configuracoes.db_itensmenu set libcliente = true where id_item in(9491, 9492,10179)");
    }
}
