<?php

use Classes\PostgresMigration;

class M12932MigracaoDados extends PostgresMigration
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
        $this->execute("update empagedadosretmov set e76_processado = false
where  e76_codmov in(
    select e76_codmov
    from empagedadosretmov
             inner join empord on e82_codmov = e76_codmov
             left join corempagemov on k12_codmov = e76_codmov
             inner join pagordemele on e82_codord = e53_codord
    where e76_processado is true
      and k12_codmov is null
      and round(e53_valor - e53_vlranu - e53_vlrpag, 2) > 0
    );
");

        $this->execute("update empagedadosretmov set e76_processado = false
where  e76_codmov in(
select e76_codmov
from empagedadosretmov
         inner join empageslip on e89_codmov = e76_codmov
         inner join slip on e89_codigo = k17_codigo
where e76_processado is true
  and k17_situacao <> 2
    );"
        );
    }
}
