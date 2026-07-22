<?php

use Classes\PostgresMigration;

class M15068AjusteContasPlano extends PostgresMigration
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

        $sql = <<<SQL
update conplano
set c60_saldocontinuo = (case
                           when         ( substr(c60_estrut, 1, 1)::integer in (1, 2, 7, 8) or
                                          substr(c60_estrut, 1, 2)::integer in (53, 63) )
                                and not substr(c60_estrut, 1, 5) ilike '82114%'
                                then true
                           when substr(c60_estrut, 1, 5) ilike '82114%' then false
                           else false
                         end)
where  c60_anousu >= 2019;
SQL;
        $this->execute($sql);

    }

    public function down()
    {
        $sql = <<<SQL
update conplano
set c60_saldocontinuo = null
where  c60_anousu >= 2019;

SQL;
        $this->execute($sql);

    }
}
