<?php

use Classes\PostgresMigration;

class M13939InsercaoCampoCadastroImobiliario extends PostgresMigration
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
        $sql = "
            ALTER TABLE iptuant ADD COLUMN j40_registrocartografico VARCHAR(20);
            INSERT INTO db_syscampo VALUES(1010817,
                                           'j40_registrocartografico',
                                           'varchar(20)',
                                           'Armazenar código para localizar imóveis.',
                                           '', 'Registro Cartográfico',20,'t','f','f',0,
                                           'text','Registro Cartográfico');
            INSERT INTO db_sysarqcamp VALUES(29,1010817,3,0);
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            ALTER TABLE iptuant DROP COLUMN j40_registrocartografico;
            DELETE FROM db_sysarqcamp WHERE codcam = 1010817;
            DELETE FROM db_syscampo WHERE codcam = 1010817;
        ";
        $this->execute($sql);
    }
}
