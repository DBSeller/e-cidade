<?php

use Classes\PostgresMigration;

class M15650AcertoAtributoAi extends PostgresMigration
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
update contabilidade.conplanoinfocomplementar
set c121_sql = 'select min(e60_anousu) as ano from conlancamemp inner join empresto on c75_numemp = e91_numemp inner join empempenho on e60_numemp = e91_numemp  where c75_codlan = codigo_lancamento limit 1'
where c121_sequencial = 51
SQL
);
    }
}
