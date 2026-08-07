<?php

use Classes\PostgresMigration;

class M16283AddCampoDescricaoNovaColetaLabRequiitem extends PostgresMigration
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
        $sql = "
            INSERT INTO db_syscampo VALUES(1011868,'la21_motivonovacoleta','text','Este campo  usado para descrever o motivo de quando  preciso de uma nova coleta.','', 'Descrio do motivo de nova coleta',255,'t','f','f',2,'text','Descrio do motivo de nova coleta');
            INSERT INTO db_sysarqcamp VALUES(2771,1011868,11,0);
            
            ALTER TABLE lab_requiitem ADD COLUMN la21_motivonovacoleta text;

            UPDATE lab_requiitem SET la21_c_situacao = '10 - Nao Digitado' where la21_c_situacao = '1 - Nao Digitado';
            UPDATE lab_requiitem SET la21_c_situacao = '50 - Lancado' where la21_c_situacao = '2 - Lancado';
            UPDATE lab_requiitem SET la21_c_situacao = '70 - Entregue' where la21_c_situacao = '3 - Entregue';
            UPDATE lab_requiitem SET la21_c_situacao = '30 - Coletado' where la21_c_situacao = '6 - Coletado';
            UPDATE lab_requiitem SET la21_c_situacao = '60 - Conferido' where la21_c_situacao = '7 - Conferido';
            UPDATE lab_requiitem SET la21_c_situacao = '20 - Autorizado' where la21_c_situacao = '8 - Autorizado';
            ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DELETE FROM db_sysarqcamp where codarq = 2771;
            DELETE FROM db_syscampo where codcam = 1011868;

            ALTER TABLE lab_requiitem DROP COLUMN la21_motivonovacoleta;

            UPDATE lab_requiitem SET la21_c_situacao = '1 - Nao Digitado' where la21_c_situacao = '10 - Nao Digitado';
            UPDATE lab_requiitem SET la21_c_situacao = '2 - Lancado' where la21_c_situacao = '50 - Lancado';
            UPDATE lab_requiitem SET la21_c_situacao = '3 - Entregue' where la21_c_situacao = '70 - Entregue';
            UPDATE lab_requiitem SET la21_c_situacao = '6 - Coletado' where la21_c_situacao = '30 - Coletado';
            UPDATE lab_requiitem SET la21_c_situacao = '7 - Conferido' where la21_c_situacao = '60 - Conferido';
            UPDATE lab_requiitem SET la21_c_situacao = '8 - Autorizado' where la21_c_situacao = '20 - Autorizado';
            ";
        $this->execute($sql);
    }  
}
