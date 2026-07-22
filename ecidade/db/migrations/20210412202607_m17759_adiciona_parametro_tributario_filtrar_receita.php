<?php

use Classes\PostgresMigration;

class M17759AdicionaParametroTributarioFiltrarReceita extends PostgresMigration
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

insert into db_syscampo values(1013157,'k03_filtrarreceita','bool','Filtrar as receitas ao buscar as regras de parcelamento. Caso seja falso não leva em consideração as receitas informadas no cadastro da regra de parcelamento. ','f', 'Filtrar Receitas',1,'f','f','f',5,'text','Filtrar Receitas');
insert into db_sysarqcamp values(318,1013157,76,0);

alter table numpref add column k03_filtrarreceita boolean default false;

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

delete from db_sysarqcamp where codarq = 318 and codcam = 1013157;
delete from db_syscampo where codcam = 1013157 and nomecam = 'k03_filtrarreceita';

alter table numpref drop column k03_filtrarreceita;

SQL
        );
    }
	
}
