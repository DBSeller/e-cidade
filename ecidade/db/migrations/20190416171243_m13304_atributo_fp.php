<?php

use Classes\PostgresMigration;

class M13304AtributoFp extends PostgresMigration
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
      update conplano
set c60_identificadorfinanceiro = 'N'
where c60_identificadorfinanceiro not in ('P', 'F')
  and c60_anousu >= 2017;
SQL
);


        $this->execute(<<<SQL
      update conplanoinfocomplementar set c121_sql = 'SELECT case when c60_identificadorfinanceiro = ''N'' then ''''
            when c60_identificadorfinanceiro = ''F'' then ''1''
            when c60_identificadorfinanceiro = ''P'' then ''2''
           end as infocomplementar_valor
FROM conplanoreduz
       INNER JOIN conplano ON c61_codcon = c60_codcon
                          AND c61_anousu = c60_anousu
WHERE c61_reduz = conta_reduzida
  AND c61_anousu = anousu
  AND c61_instit = instituicao limit 1'
where c121_sigla = 'FP';
SQL
        );
    }
}
