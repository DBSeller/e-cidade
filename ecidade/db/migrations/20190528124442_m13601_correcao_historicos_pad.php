<?php

use Classes\PostgresMigration;

class M13601CorrecaoHistoricosPad extends PostgresMigration
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
                          insert into conlancamcompl
                               select c70_codlan, 
                                      'REF LANÇAMENTO CONTÁBIL REALIZADO NO EVENTO'||' '||c71_coddoc||'-'||c53_descr||' '||'EM'||' '||c70_data as c72_complem 
                                 from conlancam 
                                      inner join conlancamdoc on c70_codlan = c71_codlan 
                                      inner join conhistdoc on c71_coddoc = c53_coddoc 
                                      left join conlancamcompl on c70_codlan = c72_codlan 
                                where c72_codlan is null 
                                  and c70_data >= '2019-01-01' ;
SQL_UP
        );
    }
}
