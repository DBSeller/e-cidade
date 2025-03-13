<?php

use Classes\PostgresMigration;

class M16572CriacaoMenuRegredirSituacao extends PostgresMigration
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
        $this->execute("
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228355 ,'Regredir Situação do Exame' ,'Regredir Status do Exame' ,'lab4_regredirsituacao001.php' ,'1' ,'1' ,'Onde é possível regradir a situação do exame por uma requisicao.' ,'false' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8173 ,228355 ,11 ,8167 );
        ");
    }
    public function down()
    {
        $this->execute("
            delete from db_menu where id_item_filho = 228355;
            delete from db_itensmenu where id_item = 228355;
        ");
    }
}
