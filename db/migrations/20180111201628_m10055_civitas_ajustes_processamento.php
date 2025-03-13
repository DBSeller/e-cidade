<?php

use Classes\PostgresMigration;

class M10055CivitasAjustesProcessamento extends PostgresMigration
{
    function up()
    {
        $this->table('atualizacaoiptuschemamotivorejeicao', array('schema'=>'cadastro', 'id'=>false, 'primary_key'=>array('j146_atualizacaoiptuschemamatricula'), 'constraint'=>'atualizacaoiptuschemamotivorejeicao_pk'))
          ->addColumn('j146_atualizacaoiptuschemamatricula', 'integer')
          ->addColumn('j146_motivo_rejeicao',                'string')
          ->addForeignKey('j146_atualizacaoiptuschemamatricula', 'cadastro.atualizacaoiptuschemamatricula ',   'j144_sequencial', array('constraint'=>'atualizacaoiptuschemamotivorejeicao_fk'))
          ->save();
        
        $this->table('atualizacaoiptuschemamatricula', array('schema'=>'cadastro'))
          ->addColumn('j144_processado',                'boolean', array('default'=>'false'))
          ->save();

        $sql = <<<SQL
        INSERT INTO db_syscampo values(1009605,'j144_processado','bool','Define se a matrícula foi processada.','f', 'Processado',1,'f','f','f',5,'text','Processado');
        INSERT INTO db_syscampodef values(1009605,'f','');
        DELETE FROM db_sysarqcamp where codcam = 1009605;
        INSERT INTO db_sysarqcamp values(1010219,1009605,5,0);
        
        INSERT INTO db_sysarquivo values (1010254, 'atualizacaoiptuschemamotivorejeicao', 'Tabela que guarda o motivo de rejeição.', 'j146', '2018-01-11', 'Tabela que guarda o motivo de rejeição.', 0, 'f', 't', 't', 't' );
        INSERT INTO db_sysarqmod values (2,1010254);
        INSERT INTO db_syscampo values(1009606,'j146_atualizacaoiptuschemamatricula','int4','Código sequencial da atualização de matrículas','0', 'Código',19,'f','f','f',1,'text','Código');
        INSERT INTO db_syscampo values(1009607,'j146_motivo_rejeicao','text','Motivo da Rejeição','', 'Motivo da Rejeição',255,'f','t','f',0,'text','Motivo da Rejeição');
        DELETE FROM db_sysarqcamp where codarq = 1010254;
        INSERT INTO db_sysarqcamp values(1010254,1009606,1,0);
        INSERT INTO db_sysarqcamp values(1010254,1009607,2,0);
        DELETE FROM db_sysprikey where codarq = 1010254;
        INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) values(1010254,1009606,1,1009606);
        DELETE FROM db_sysforkey where codarq = 1010254 and referen = 0;
        INSERT INTO db_sysforkey values(1010254,1009606,1,1010219,0);
SQL;
        $this->execute($sql);
    }

    function down()
    {
        $this->table('atualizacaoiptuschemamotivorejeicao', array('schema'=>'cadastro'))->drop();
        $this->table('atualizacaoiptuschemamatricula', array('schema'=>'cadastro'))->removeColumn('j144_processado')->save();
        
        $sql = <<<SQL
        DELETE FROM db_sysarqcamp where codcam = 1009605;
        DELETE FROM db_syscampodef WHERE codcam = 1009605;
        DELETE FROM db_syscampo WHERE codcam = 1009605;
        
        DELETE FROM db_sysforkey where codarq = 1010254;
        DELETE FROM db_sysprikey where codarq = 1010254;
        DELETE FROM db_sysarqcamp where codarq = 1010254;
        DELETE FROM db_syscampo WHERE codcam IN (1009606, 1009607);
        DELETE FROM db_sysarqmod WHERE codarq = 1010254;
        DELETE FROM db_sysarquivo WHERE codarq = 1010254;
SQL;

        $this->execute($sql);
    }
}
