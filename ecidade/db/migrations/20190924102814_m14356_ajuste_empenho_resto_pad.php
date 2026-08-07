<?php

use Classes\PostgresMigration;

class M14356AjusteEmpenhoRestoPad extends PostgresMigration
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

        $retorno = $this->query("select count(*) as total from db_plugin where db145_nome = 'ContratosPADRS' and db145_situacao is true");

        $total  = $retorno->fetch(PDO::FETCH_ASSOC);
        if ($total["total"] == 0) {
            return true;
        }
        $this->execute(<<<SQL

insert into plugins.padrsdespesafuncionarioempenhovinculo
select nextval('plugins.padrsdespesafuncionarioempenhovinculo_sequencial_seq'::regclass),
       e60_numemp,
       1
from empempenho
         inner join empelemento on e64_numemp = e60_numemp
         inner join orcelemento on e64_codele = o56_codele
    and e60_anousu = o56_anousu
         inner join empresto on e91_numemp = e60_numemp
where substr(o56_elemento, 1, 3) = '331'
  and e60_instit = 1
  and not exists(select 1
                 from plugins.padrsdespesafuncionarioempenhovinculo
                 where empempenho = e60_numemp
    )
  and e91_anousu = 2019
and exists(select 1 from db_plugin where db145_nome = 'ContratosPADRS' and db145_situacao is true);
SQL

        );


        $this->execute(<<<SQL
update plugins.padrsdespesafuncionarioempenhovinculo
set padrsdespesafuncionario = 1
from empempenho,
     orcelemento,
     empelemento,
     empresto
where substr(o56_elemento, 1, 3) = '331'
  and empempenho.e60_numemp = plugins.padrsdespesafuncionarioempenhovinculo.empempenho
  and e64_numemp = e60_numemp
  and e64_codele = o56_codele
  and e60_anousu = o56_anousu
  and e91_numemp = e60_numemp
  and e91_anousu = 2019
  and exists(select 1 from db_plugin where db145_nome = 'ContratosPADRS' and db145_situacao is true)
SQL

        );
    }
}
