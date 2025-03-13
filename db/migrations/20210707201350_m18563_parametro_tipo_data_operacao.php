<?php

use Classes\PostgresMigration;

class M18563ParametroTipoDataOperacao extends PostgresMigration
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

insert into db_syscampo values(1013324,'y32_tipodataoperacao','int4','Data de operação utilizada ao exportar levantamento fiscal','1', 'Tipo Data Operação ',1,'f','f','f',1,'text','Tipo Data Operação ');
insert into db_syscampodef values(1013324,'1 Data operação igual a data do vencimento','');
insert into db_syscampodef values(1013324,'2 Data operação igual a data do levantamento','');
insert into db_sysarqcamp values(1103,1013324,25,0);

alter table parfiscal add column y32_tipodataoperacao integer not null default 1;

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

delete from db_sysarqcamp where codarq = 1103 and codcam = 1013324;
delete from db_syscampodef where codcam = 1013324;
delete from db_syscampo where codcam = 1013324;

alter table parfiscal drop column y32_tipodataoperacao;

SQL
        );
}
	
}

