<?php

use Classes\PostgresMigration;

class M12259RecursoMatriSaldoContabil extends PostgresMigration
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
        $this->execute("update conplanoinfocomplementar set c121_sql = 'select c130_orctiporec from conlancamrecurso where c130_conlancam = codigo_lancamento and c130_conta = conta_reduzida and c130_natureza = natureza' where c121_nomepropriedade = 'atributo_fr'");

    }
}
