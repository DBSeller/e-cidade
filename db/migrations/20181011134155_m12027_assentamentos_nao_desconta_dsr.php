<?php

use Classes\PostgresMigration;

class M12027AssentamentosNaoDescontaDsr extends PostgresMigration
{
    function up ()
    {
        $this->upDDL();
        $this->upDicionarioDados();
    }

    function down ()
    {
        $this->downDicionarioDados();
        $this->downDDL();
    }

    function upDDL()
    {
        $this->execute("CREATE SEQUENCE recursoshumanos.pontoeletronicoassentamentosnaoperdedsr_seq");
        $this->table('pontoeletronicoassentamentosnaoperdedsr', array('schema'=>'recursoshumanos', 'id'=>false, 'primary_key'=>array('rh218_sequencial'), 'constraint'=>'pontoeletronicoassentamentosnaoperdedsr_pk'))
             ->addColumn('rh218_sequencial',  'integer')
             ->addColumn('rh218_instituicao', 'integer')
             ->addColumn('rh218_tipoasse',    'integer')
             ->addForeignKey('rh218_tipoasse', 'recursoshumanos.tipoasse',   'h12_codigo', array('constraint'=>'tipoasse_pontoeletronicoassentamentosnaoperdedsr_fk'))
             ->addIndex(array('rh218_instituicao', 'rh218_tipoasse'), array('unique'=>true, 'name'=>'pontoeletronicoassentamentosnaoperdedsr_un_in'))
             ->save();
        
        $sqls = array(
            "ALTER TABLE recursoshumanos.pontoeletronicoassentamentosnaoperdedsr ALTER COLUMN rh218_sequencial SET DEFAULT nextval('pontoeletronicoassentamentosnaoperdedsr_seq'::regclass)",
            "ALTER TABLE recursoshumanos.pontoeletronicoassentamentosnaoperdedsr ALTER COLUMN rh218_instituicao SET DEFAULT fc_getsession('DB_instit')::int",
        );

        $this->sqlsExecutar($sqls);
    }

    function upDicionarioDados()
    {
        $sqls = array(
            "INSERT INTO db_sysarquivo
            VALUES(1010327,'pontoeletronicoassentamentosnaoperdedsr','Configurações de assentamentos que não perdem DSR','rh218','2018-10-11','Configurações de assentamentos que não perdem DSR',0,'t','f','t','t');",
            
            "INSERT INTO db_syscampo
            VALUES(1010012,'rh218_sequencial','int4','Código sequencial da tabela','0','Código sequencial da tabela',19,'f','f','f',1,'text','Código');",

            "INSERT INTO db_syscampo
            VALUES(1010013,'rh218_instituicao','int4','Instituição da configuração','0','Instituição da configuração',19,'f','f','f',1,'text','Instituição');",

            "INSERT INTO db_syscampo
            VALUES(1010014,'rh218_tipoasse','int4','Tipo de assentamento','0','Tipo de assentamento',19,'f','f','f',1,'text','Tipo de assentamento');",

            "INSERT INTO db_sysarqcamp
            VALUES(1010327,1010012,1,0);",

            "INSERT INTO db_sysarqcamp
            VALUES(1010327,1010013,2,0);",

            "INSERT INTO db_sysarqcamp
            VALUES(1010327,1010014,3,0);",

            "INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden)
            VALUES(1010327,1010012,1,1010012);",

            "INSERT INTO db_sysforkey
            VALUES(1010327,1010014,1,596,0);",

            "INSERT INTO db_sysforkey
            VALUES(1010327,1010013,1,83,0);",

            "INSERT INTO db_sysindices
            VALUES(1008332,'pontoeletronicoassentamentosnaoperdedsr_un_in',1010327,'1');",

            "INSERT INTO db_syscadind
            VALUES(1008332,1010013,1);",

            "INSERT INTO db_syscadind
            VALUES(1008332,1010014,2);",
        );

        $this->sqlsExecutar($sqls);
    }

    function sqlsExecutar(array $sqls)
    {
        if(!empty($sqls)) {
            foreach ($sqls as $sql) {
                $this->execute($sql);
            }
        }
    }

    function downDicionarioDados()
    {
        $sqls = array(
            "DELETE FROM db_syscadind WHERE codind = 1008332;",
            "DELETE FROM db_sysindices WHERE codind = 1008332;",
            "DELETE FROM db_sysforkey WHERE codarq = 1010327;",
            "DELETE FROM db_sysprikey WHERE codarq = 1010327;",
            "DELETE FROM db_sysarqcamp WHERE codarq = 1010327;",
            "DELETE FROM db_syscampo WHERE codcam IN (1010012, 1010013, 1010014);",
            "DELETE FROM db_sysarquivo WHERE codarq = 1010327;",
        );
        
        $this->sqlsExecutar($sqls);
    }

    function downDDL()
    {
        $this->table('pontoeletronicoassentamentosnaoperdedsr', array('schema'=>'recursoshumanos'))->drop();
        $this->execute("DROP SEQUENCE recursoshumanos.pontoeletronicoassentamentosnaoperdedsr_seq");
    }
}
