<?php

use Classes\PostgresMigration;

class M13291AlteraObrigatoriedadeInfraestrutura extends PostgresMigration
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
            update avaliacaopergunta set db103_obrigatoria = 'false' where db103_sequencial in (
                3000005,
                3000025,
                3000014,
                3000015,
                3000016,
                3000017,
                3000018,
                3000019,
                3000020,
                3000021,
                3000022,
                3000023,
                3000026,
                3000001,
                3000004,
                3000006,
                3000003,
                3000024,
                3000007,
                3000010,
                3000011,
                3000009,
                3000008,
                3000000,
                4000230,
                4000236,
                4000237,
                4000239,
                4000240,
                4000241,
                4000242,
                4000243,
                4000244,
                4000245,
                4000246
            );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            update avaliacaopergunta set db103_obrigatoria = 'true' where db103_sequencial in (
                3000005,
                3000025,
                3000014,
                3000015,
                3000016,
                3000017,
                3000018,
                3000019,
                3000020,
                3000021,
                3000022,
                3000023,
                3000026,
                3000001,
                3000004,
                3000006,
                3000003,
                3000024,
                3000007,
                3000010,
                3000011,
                3000009,
                3000008,
                3000000,
                4000230,
                4000236,
                4000237,
                4000239,
                4000240,
                4000241,
                4000242,
                4000243,
                4000244,
                4000245,
                4000246
            );
SQL
        );
    }
}
