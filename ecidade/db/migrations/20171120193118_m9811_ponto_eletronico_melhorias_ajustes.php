<?php

use Classes\PostgresMigration;

class M9811PontoEletronicoMelhoriasAjustes extends PostgresMigration
{
    function up() 
    {
        $this->upDDL();
        $this->upDicionarioDados();
    }

    function upDDL()
    {
        $this->table('tipoasse',     array('schema'=>'recursoshumanos'))
             ->addColumn('h12_gerafaltas', 'boolean',  array('null'=>false, 'default' => 'f'))
             ->save();

        $this->execute("CREATE SEQUENCE recursoshumanos.jornadaservidor_rh212_sequencial_seq");
        $this->table('jornadaservidor', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>array('rh212_sequencial'), 'constraint'=>'jornadaservidor_sequencial_pk'))
             ->addColumn('rh212_sequencial',    'integer', array('null'=>false))
             ->addColumn('rh212_data',          'date',    array('null'=>false))
             ->addColumn('rh212_matricula',     'integer', array('null'=>false))
             ->addColumn('rh212_jornada',       'integer', array('null'=>false))
             ->addForeignKey('rh212_matricula',  'pessoal.rhpessoal',         'rh01_regist',      array('constraint'=>'jornadaservidor_matricula_fk'))
             ->addForeignKey('rh212_jornada',    'recursoshumanos.jornada',   'rh188_sequencial', array('constraint'=>'jornadaservidor_jornada_fk'))
             ->save();
    }

    function upDicionarioDados()
    {
        // Campo de gerar faltas na tabela tipoasse
        $this->execute("INSERT INTO db_syscampo VALUES (1009520,'h12_gerafaltas','bool','Campo que define se o tipo de assentamento gera ou não faltas no ponto eletrônico.','f', 'Gerar Faltas',1,'f','f','f',5,'text','Gerar Faltas')");
        $this->execute("INSERT INTO db_syscampodef VALUES (1009520,'false','')");
        $this->execute("INSERT INTO db_sysarqcamp VALUES (596,1009520,17,0)");

        // Tabela que guarda jornada do servidor    
        $this->execute("INSERT INTO db_sysarquivo values (1010242, 'jornadaservidor', 'Tabela para vincular uma jornada ao servidor, serve para alterar a jornada de um ou alguns dias para um servidor sem a necessidade de alterar a escala.', 'rh212', '2017-11-27', 'Altera a Jornada do Servidor em um dia', 0, 'f', 'f', 't', 't' )");
        $this->execute("INSERT INTO db_sysarqmod values (29,1010242)");
        $this->execute("INSERT INTO db_syscampo values(1009535,'rh212_sequencial','int4','Sequencial da tabela','0', 'Sequencial',19,'f','f','f',1,'text','Sequencial')");
        $this->execute("INSERT INTO db_syscampo values(1009536,'rh212_data','date','Data de início de quando a jornada deve ser alterada para o servidor','null', 'Data Início',10,'f','f','f',1,'text','Data Início')");
        $this->execute("INSERT INTO db_syscampo values(1009538,'rh212_matricula','int4','Matrícula do servidor que terá sua jornada alterada','0', 'Matrícula',19,'f','f','f',1,'text','Matrícula')");
        $this->execute("INSERT INTO db_syscampo values(1009539,'rh212_jornada','int4','Jornada a qual o servidor está em uma data específica','0', 'Jornada',19,'f','f','f',1,'text','Jornada')");
        $this->execute("INSERT INTO db_sysarqcamp values(1010242,1009535,1,0)");
        $this->execute("INSERT INTO db_sysarqcamp values(1010242,1009536,2,0)");
        $this->execute("INSERT INTO db_sysarqcamp values(1010242,1009538,4,0)");
        $this->execute("INSERT INTO db_sysarqcamp values(1010242,1009539,5,0)");
        $this->execute("INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) values(1010242,1009535,1,1009535)");
        $this->execute("INSERT INTO db_sysforkey values(1010242,1009538,1,1153,0)");
        $this->execute("INSERT INTO db_sysforkey values(1010242,1009539,1,4005,0)");
        $this->execute("INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10470 ,'Manutenção de Jornada de Funcionário' ,'Manutenção de Jornada de Funcionário' ,'rec4_jornadaservidor001.php' ,'1' ,'1' ,'Menu para alterar a jornada de um ou mais dias para um ou mais servidores, irá sobrescrever a jornada da escala sem precisar alterar a escala do servidor.' ,'true' )");
        $this->execute("INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10368 ,10470 ,5 ,2323 )");
        $this->execute("INSERT INTO db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq, cacheseq) VALUES (1000702, 'jornadaservidor_rh212_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)");
        $this->execute("UPDATE db_sysarqcamp set codsequencia = 1000702 where codarq = 1010242 and codcam = 1009535");
    }

    function down()
    {
        $this->downDDL();
        $this->downDicionarioDados();
    }

    function downDDL()
    {
        $tabelaTipoasse = $this->table('tipoasse',     array('schema'=>'recursoshumanos'));
        $tabelaTipoasse->removeColumn('h12_gerafaltas');
        
        $this->table('jornadaservidor', array('schema'=>'recursoshumanos'))->drop();
        $this->execute("DROP SEQUENCE recursoshumanos.jornadaservidor_rh212_sequencial_seq");
    }

    function downDicionarioDados()
    {
        // Campo de gerar faltas na tabela tipoasse
        $this->execute("DELETE FROM db_syscampodef WHERE codcam = 1009520");
        $this->execute("DELETE FROM db_sysarqcamp WHERE codcam = 1009520");
        $this->execute("DELETE FROM db_syscampo WHERE codcam = 1009520");
        
        // Tabela que guarda jornada do servidor
        $this->execute("DELETE FROM db_sysforkey where codarq = 1010242");
        $this->execute("DELETE FROM db_sysprikey where codarq = 1010242");
        $this->execute("DELETE FROM db_sysarqcamp where codarq = 1010242");
        $this->execute("DELETE FROM db_syscampo where codcam IN (1009535, 1009536, 1009538, 1009539)");
        $this->execute("DELETE FROM db_sysarqmod where codarq = 1010242");
        $this->execute("DELETE FROM db_sysarquivo where codarq = 1010242");
        $this->execute("DELETE FROM db_menu where id_item_filho = 10470 AND modulo = 2323");
        $this->execute("DELETE FROM db_itensmenu where id_item = 10470");
        $this->execute("DELETE FROM db_syssequencia where codsequencia = 1000702");
    }
}
