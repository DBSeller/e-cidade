<?php

use Classes\PostgresMigration;

class M16866AdicionaColunasIdadeGestacionalDum extends PostgresMigration
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
        $this->execute(
<<<SQL
            INSERT INTO db_syscampo VALUES(1011890,'sd24_idadegestacional','int4','Idade Gestacional (Semanas)','0', 'Idade Gestacional (Semanas)',10,'t','f','f',1,'text','Idade Gestacional (Semanas)');
            INSERT INTO db_syscampo VALUES(1011892,'sd24_dum','date','Data da ultima menstruação','null', 'DUM',10,'t','f','f',1,'text','DUM');
            
            INSERT INTO db_sysarqcamp VALUES(1010134,1011892,22,0);
            INSERT INTO db_sysarqcamp VALUES(1010134,1011890,23,0);

            ALTER TABLE prontuarios ADD COLUMN sd24_idadegestacional INTEGER;
            ALTER TABLE prontuarios ADD COLUMN sd24_dum DATE;

            UPDATE prontuarios p
            SET sd24_dum = t.s152_dum
            FROM sau_triagemavulsaprontuario tp
            JOIN sau_triagemavulsa t on tp.s155_i_triagemavulsa = t.s152_i_codigo
            WHERE tp.s155_i_prontuario = p.sd24_i_codigo AND t.s152_dum is not null;
            
            UPDATE prontuarios p
            SET sd24_idadegestacional = t.s152_idadegestacional
            FROM sau_triagemavulsaprontuario tp
            JOIN sau_triagemavulsa t on tp.s155_i_triagemavulsa = t.s152_i_codigo
            WHERE tp.s155_i_prontuario = p.sd24_i_codigo AND t.s152_idadegestacional is not null;

            DELETE FROM db_sysarqcamp WHERE codcam = 1011802;
            DELETE FROM db_syscampo WHERE codcam = 1011802;

            ALTER TABLE sau_triagemavulsa DROP COLUMN s152_idadegestacional;
SQL
            );
    }

    public function down()
    {
        $this->execute(
<<<SQL
            DELETE FROM db_sysarqcamp WHERE codcam = 1011890;
            DELETE FROM db_sysarqcamp WHERE codcam = 1011892;
            
            DELETE FROM db_syscampo WHERE codcam = 1011890;
            DELETE FROM db_syscampo WHERE codcam = 1011892;

            ALTER TABLE prontuarios DROP COLUMN sd24_idadegestacional;
            ALTER TABLE prontuarios DROP COLUMN sd24_dum;

            INSERT INTO db_syscampo VALUES(1011802,'s152_idadegestacional','int4','Idade gestacional em semanas','0', 'Idade Gestacional (Semanas)',10,'t','f','f',1,'text','Idade Gestacional (Semanas)');
            INSERT INTO db_sysarqcamp VALUES(3043,1011802,23,0);

            ALTER TABLE sau_triagemavulsa ADD COLUMN s152_idadegestacional INTEGER;
SQL
        );
    }
}
