<?php

use Classes\PostgresMigration;

class M11191ConsultaContaCorrente extends PostgresMigration
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

        $this->execute("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228065 ,'Conta Corrente' ,'Conta Corrente' ,'' ,'1' ,'1' ,'Consultas do conta corrente' ,'true' );");
        $this->execute("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3333 ,228065 ,22 ,209 );");
        $this->execute("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228066 ,'Consulta Geral' ,'Consulta Geral' ,'con2_consultacontacorrente001.php' ,'1' ,'1' ,'Consulta geral da conta corrente' ,'true' );");
        $this->execute("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228065 ,228066 ,1 ,209 );");
    }

    public  function down()
    {
        $this->execute("delete from db_menu where id_item_filho = 228065 AND modulo = 209;");
        $this->execute("delete from db_menu where id_item_filho = 228066 AND modulo = 209;");
        $this->execute("delete from db_itensmenu where id_item in(228065, 228066)");

    }
}
