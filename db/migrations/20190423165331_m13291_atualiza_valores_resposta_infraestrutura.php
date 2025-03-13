<?php

use Classes\PostgresMigration;

class M13291AtualizaValoresRespostaInfraestrutura extends PostgresMigration
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
            update avaliacaoperguntaopcao set db104_valorresposta = 0 where db104_sequencial in (
                3000102,
                4001290,
                3000031,
                3000123,
                3000038,
                3000098,
                3000099,
                3000111,
                3000562,
                4001283,
                4001273,
                4001281,
                3000125
            );


            update avaliacaoperguntaopcao set db104_valorresposta = 1 where db104_sequencial in (
                3000047,
                3000101,
                4001291,
                3000030,
                3000122,
                3000037,
                3000097,
                3000100,
                3000112,
                3000561,
                3000124,
                4001282,
                4001280,
                4001272
            );


            update avaliacaoperguntaopcao set db104_valorresposta = 2 where db104_sequencial in (
                3000048,
                4001292
            );


            update avaliacaoperguntaopcao set db104_valorresposta = 3 where db104_sequencial in (
                3000049
            );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            update avaliacaoperguntaopcao set db104_valorresposta = null where db104_sequencial in (
                3000049,
                3000048,
                4001292,
                3000047,
                3000101,
                4001291,
                3000030,
                3000122,
                3000037,
                3000097,
                3000100,
                3000112,
                3000561,
                3000124,
                4001282,
                4001280,
                4001272,
                3000102,
                4001290,
                3000031,
                3000123,
                3000038,
                3000098,
                3000099,
                3000111,
                3000562,
                4001283,
                4001273,
                4001281,
                3000125
            );
SQL
        );
    }
}
