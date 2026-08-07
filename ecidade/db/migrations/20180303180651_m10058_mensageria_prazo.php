<?php

use Classes\PostgresMigration;

class M10058MensageriaPrazo extends PostgresMigration
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
            insert into db_syscampo values(1009652,'p101_tipoprazo','int8','Tipo de Prazo para definir de onde pegar o prazo de envio de notificação','0', 'Tipo de Prazo',11,'f','f','f',1,'text','');
            delete from db_sysarqcamp where codarq = 1010238;
            
            insert into db_sysarqcamp values(1010238,1009522,1,1000699);
            insert into db_sysarqcamp values(1010238,1009523,2,0);
            insert into db_sysarqcamp values(1010238,1009524,3,0);
            insert into db_sysarqcamp values(1010238,1009525,4,0);
            insert into db_sysarqcamp values(1010238,1009526,5,0);
            insert into db_sysarqcamp values(1010238,1009527,6,0);
            insert into db_sysarqcamp values(1010238,1009652,7,0);
            
            
            insert into db_syscampo values(1009665,'p101_usuarioremetente','int8','Usuário Remetente, do envio da notificação','0', 'Usuário Remetente',11,'f','f','f',1,'text','Usuário Remetente');
            delete from db_sysarqcamp where codarq = 1010238;
            insert into db_sysarqcamp values(1010238,1009522,1,1000699);
            insert into db_sysarqcamp values(1010238,1009523,2,0);
            insert into db_sysarqcamp values(1010238,1009524,3,0);
            insert into db_sysarqcamp values(1010238,1009525,4,0);
            insert into db_sysarqcamp values(1010238,1009526,5,0);
            insert into db_sysarqcamp values(1010238,1009527,6,0);
            insert into db_sysarqcamp values(1010238,1009652,7,0);
            insert into db_sysarqcamp values(1010238,1009665,8,0);

            
            
            ALTER TABLE mensageriaprocesso ADD COLUMN p101_tipoprazo integer;
            ALTER TABLE mensageriaprocesso ADD COLUMN p101_usuarioremetente integer;
             
       ");
    }

    public function down()
    {
        $this->execute("
        
              DELETE FROM db_sysarqcamp WHERE  codarq = 1010238;
              DELETE FROM db_syscampo   WHERE  codcam = 1009652;
              DELETE FROM db_syscampo   WHERE  codcam = 1009665;
                            
              ALTER TABLE mensageriaprocesso DROP COLUMN p101_tipoprazo;
              ALTER TABLE mensageriaprocesso DROP COLUMN p101_usuarioremetente;
        ");

    }


}
