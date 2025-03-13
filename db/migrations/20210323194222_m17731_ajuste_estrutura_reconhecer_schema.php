<?php

use Classes\PostgresMigration;

class M17731AjusteEstruturaReconhecerSchema extends PostgresMigration
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
        $this->execute(<<<SQL
        
        select fc_set_pg_search_path();
       
        create or replace function fc_atualiza_search_path_inc_del_alt() returns trigger as
        $$
        begin

          perform fc_set_pg_search_path();
        end;
        $$
        language 'plpgsql';

        CREATE TRIGGER tg_atualiza_search_path
        AFTER UPDATE or DELETE or INSERT ON db_sysmodulo
        FOR EACH ROW
        EXECUTE PROCEDURE fc_atualiza_search_path_inc_del_alt();
SQL
    );
    }

    public function down()
    {
        $this->execute(<<<SQL
        DROP TRIGGER tg_atualiza_search_path on db_sysmodulo;
        drop function fc_atualiza_search_path_inc_del_alt();
SQL
        );
    }
}
