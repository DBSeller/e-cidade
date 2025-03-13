<?php

use Classes\PostgresMigration;

class M13306CorrecaoHistoricosLancamentosContabeis extends PostgresMigration
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
        $this->execute(<<<SQL_UP
            INSERT into conlancamcompl 
                select c70_codlan,
                ('Lancamento contabil correspondente ao evento'||' '||c71_coddoc||'-'||c53_descr||' '||'em'||' '||c70_data)::varchar 
                  from conlancam 
                       inner join conlancamdoc   on c70_codlan = c71_codlan 
                       left  join conlancamcompl on c70_codlan = c72_codlan 
                       inner join conhistdoc     on c71_coddoc = c53_coddoc 
                 where
                   not exists(select 1 from conlancamcompl where c72_codlan = c70_codlan)
                   and c70_anousu = 2019 
                   and c72_codlan is null;
SQL_UP
        );
    }
}
