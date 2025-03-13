<?php

use Classes\PostgresMigration;

class M15356CampoArquivo4111 extends PostgresMigration
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
        $this->execute("
        insert into db_layoutcampos(db52_codigo, db52_layoutlinha, db52_nome, db52_descr, db52_layoutformat, db52_posicao,
                            db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs,
                            db52_quebraapos)
values ((select max(db52_codigo) + 1 from db_layoutcampos), 132, 'complemento_recurso', 'COMPLEMENTO DO RECURSO', 1, 261, '0000', 4, 'f', 't', 'd', '', 0);");
    }

    public function down()
    {
        $this->execute("delete from db_layoutcampos where db52_layoutlinha = 132 and  db52_nome = 'complemento_recurso'");
    }
}
