<?php

use Classes\PostgresMigration;

class M19126AlteracaoTipoFormularioEsocialDesligamentoServidor extends PostgresMigration
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
        $sql = "UPDATE avaliacaopergunta SET db103_tipo = 1 WHERE db103_avaliacaogrupopergunta in (3000425, 3000502);";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "UPDATE avaliacaopergunta SET db103_tipo = 10 WHERE db103_avaliacaogrupopergunta in (3000425, 3000502);";
        $this->execute($sql);
    }
}
