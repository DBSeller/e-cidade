<?php

use Classes\PostgresMigration;

class M19033ConsultarDocumentoSisobra extends PostgresMigration
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
        $this->upDicionario();
    }

    public function down()
    {
        $this->downDicionario();
        
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228574 ,'Documento Sisobra' ,'Consulta Alvará ou Habite-se via webserive do Sisobra' ,'pro3_consultasisobra001.php' ,'1' ,'1' ,'Consulta Alvará ou Habite-se via webserive do Sisobra' ,'true' );
            delete from db_menu where id_item_filho = 228574 AND modulo = 3675;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1822 ,228574 ,28 ,3675 );
SQL
        );
        
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho = 228574 AND modulo = 3675;
            delete from db_itensmenu where id_item=228574 and descricao='Documento Sisobra' and help='Consulta Alvará ou Habite-se via webserive do Sisobra' and funcao='pro3_consultasisobra001.php' and itemativo='1' and manutencao='1' and desctec='Consulta Alvará ou Habite-se via webserive do Sisobra' and libcliente='true';           
SQL
        );
    }
}
