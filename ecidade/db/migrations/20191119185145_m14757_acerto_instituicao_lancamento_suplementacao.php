<?php

use Classes\PostgresMigration;

class M14757AcertoInstituicaoLancamentoSuplementacao extends PostgresMigration
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
create temp table w_acerto_14757 as select c70_codlan, c70_data, c73_coddot, c02_instit, o58_instit 
from contabilidade.conlancam 
    inner join contabilidade.conlancaminstit on c02_codlan = c70_codlan 
    inner join contabilidade.conlancamdot on c73_codlan = c70_codlan 
    inner join orcamento.orcdotacao on c73_coddot = o58_coddot 
                                   and c73_anousu = o58_anousu 
where c70_anousu = 2019 and c02_instit <> o58_instit;
SQL
        );

        $this->execute(<<<SQL
update contabilidade.conlancaminstit
   set c02_instit = o58_instit 
  from w_acerto_14757 
 where conlancaminstit.c02_codlan = w_acerto_14757.c70_codlan;
SQL
        );
    }
}
