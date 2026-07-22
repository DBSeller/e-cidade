<?php

use Classes\PostgresMigration;

class M14269AjusteAtributoEsMsc extends PostgresMigration
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
update contabilidade.conplanoinfocomplementar set c121_sql = '
select case when (finalidade_dotacao.c119_tipo is null and finalidade_empenho.c119_tipo is null) then ''0''
            when (c73_codlan is not null) then finalidade_dotacao.c119_tipo
            when (finalidade_empenho.c119_tipo is not null) then finalidade_empenho.c119_tipo
           end as tipo
from conlancam
         left join contabilidade.conlancamemp on c75_codlan  = c70_codlan
         left join empenho.empempenho on c75_numemp  = e60_numemp
         left join siconfidotacaofinalidade finalidade_empenho on finalidade_empenho.c119_coddot = e60_coddot
                                                              and finalidade_empenho.c119_anousu = e60_anousu
         left join conlancamdot on c70_codlan = c73_codlan
         left join siconfidotacaofinalidade finalidade_dotacao on finalidade_dotacao.c119_coddot = c73_coddot
                        and finalidade_dotacao.c119_anousu = c73_anousu
where c70_codlan = codigo_lancamento limit 1'
where c121_sequencial = 50
SQL
        );
    }
    public function down()
    {
       return true;
    }
}
