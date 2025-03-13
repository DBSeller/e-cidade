<?php

use Classes\PostgresMigration;

class M16146TabelaCamposandpadraoresposta extends PostgresMigration
{
    public function up()
    {
        $this->upDicionarioDados();
        $this->upDDL();
    }
    
    public function upDDL()
    {
        $this->execute("CREATE SEQUENCE protocolo.camposandpadraoresposta_p111_sequencial_seq");

        $tabela = $this->table('camposandpadraoresposta', [
            'schema' => 'protocolo',
            'id' => 'p111_sequencial',
            'primary_key' => [
                'p111_sequencial'
            ],
            'constraint' =>' camposandpadrao_p111_sequencial_pk'
        ]);
        $tabela
            ->addColumn('p111_camposandpadrao',  'integer')
            ->addColumn('p111_codandam',         'integer')
            ->addColumn('p111_codcam',           'integer', [ 'null' => 'true' ])
            ->addColumn('p111_resposta',         'text',    [ 'null' => 'true' ])
            ->addForeignKey('p111_camposandpadrao',      'protocolo.camposandpadrao',  'p110_sequencial')
            ->addForeignKey('p111_codandam',             'protocolo.procandam',        'p61_codandam')
            ->addForeignKey('p111_codcam',               'configuracoes.db_syscampo',  'codcam')
            ->create();

        $this->execute("
            ALTER TABLE protocolo.camposandpadraoresposta
                ALTER COLUMN p111_sequencial 
                SET DEFAULT nextval('protocolo.camposandpadraoresposta_p111_sequencial_seq')
        ");

        $trigger = <<<TRIGGER_CODCAM
            DROP TRIGGER IF EXISTS tg_getcodigocampobycamposandpadrao ON camposandpadraoresposta;

            CREATE OR REPLACE FUNCTION public.fc_getcodigocampobycamposandpadrao()
             RETURNS TRIGGER AS
            $$
            BEGIN

                SELECT p110_codcam 
                  INTO NEW.p111_codcam
                  FROM camposandpadrao
                 WHERE p110_sequencial = NEW.p111_camposandpadrao;

                RETURN NEW;
            END;
            $$
            LANGUAGE 'plpgsql';

            CREATE TRIGGER tg_getcodigocampobycamposandpadrao BEFORE INSERT OR UPDATE
            ON camposandpadraoresposta FOR EACH ROW 
            EXECUTE PROCEDURE fc_getcodigocampobycamposandpadrao();
TRIGGER_CODCAM;
        
        $this->execute($trigger);
    }

    public function upDicionarioDados()
    {
        $sqlDicionario = <<<SQL_DICIONARIO_UP
            INSERT INTO db_sysarquivo values (1010607, 'camposandpadraoresposta', 'Tabela que guarda as resposta dos campos dinâmicos do andamento, populada ao conceder despacho ao processo', 'p111', '2020-07-16','camposandpadraoresposta', 0, 'f', 't', 't', 't');
            
            INSERT INTO db_sysarqmod values (4, 1010607);

            INSERT INTO db_syscampo values(1011725,'p111_sequencial','int8','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código');
            INSERT INTO db_syscampo values(1011726,'p111_camposandpadrao','int8','Vínculo do campo com o andamento padrão','0', 'Campo andamento',19,'f','f','f',1,'text','Campo andamento');
            INSERT INTO db_syscampo values(1011727,'p111_codandam','int8','Andamento atual do processo, ao qual a resposta pertence','0', 'Andamento',19,'f','f','f',1,'text','Andamento');
            INSERT INTO db_syscampo values(1011728,'p111_codcam','int8','Código do campo','0', 'Campo',19,'t','f','f',1,'text','Campo');
            INSERT INTO db_syscampo values(1011729,'p111_resposta','text','Resposta do campo dinâmico','', 'Resposta',1,'t','t','f',0,'text','Resposta');
            
            INSERT INTO db_sysarqcamp values(1010607,1011725,1,0);
            INSERT INTO db_sysarqcamp values(1010607,1011726,2,0);
            INSERT INTO db_sysarqcamp values(1010607,1011727,3,0);
            INSERT INTO db_sysarqcamp values(1010607,1011728,4,0);
            INSERT INTO db_sysarqcamp values(1010607,1011729,5,0);
            
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) values(1010607,1011725,1,1011725);
            
            INSERT INTO db_sysforkey values(1010607,1011727,1,407,0);
            INSERT INTO db_sysforkey values(1010607,1011726,1,1010595,0);
            
            INSERT INTO db_sysindices values(1008589,'camposandpadraoresposta_pk_in',1010607,'0');
            INSERT INTO db_syscadind values(1008589,1011725,1);
            INSERT INTO db_sysindices values(1008590,'camposandpadraoresposta_camposandpadrao_in',1010607,'0');
            INSERT INTO db_syscadind values(1008590,1011726,1);
            
            INSERT INTO db_syssequencia values(1000961, 'camposandpadraoresposta_p111_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000961 where codarq = 1010607 and codcam = 1011725;

SQL_DICIONARIO_UP;

        $this->execute($sqlDicionario);
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downDDL();
    }
    
    public function downDDL()
    {
        $this->execute("DROP TRIGGER tg_getcodigocampobycamposandpadrao ON camposandpadraoresposta");
        $this->execute("DROP TABLE protocolo.camposandpadraoresposta");
        $this->execute("DROP SEQUENCE protocolo.camposandpadraoresposta_p111_sequencial_seq");
    }

    public function downDicionarioDados()
    {
        $sqlDicionario = <<<SQL_DICIONARIO_DOWN
            DELETE FROM db_syssequencia WHERE codsequencia = 1000961;
            DELETE FROM db_syscadind WHERE codind IN (1008590, 1008589);
            DELETE FROM db_sysindices WHERE codind IN (1008590, 1008589);
            DELETE FROM db_sysforkey WHERE codarq = 1010607;
            DELETE FROM db_sysprikey WHERE codarq = 1010607;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010607;
            DELETE FROM db_syscampo WHERE codcam IN (1011725, 1011726, 1011727, 1011728, 1011729);

            DELETE FROM db_sysarqmod WHERE codarq = 1010607;
            DELETE FROM db_sysarquivo WHERE codarq = 1010607;
SQL_DICIONARIO_DOWN;

        $this->execute($sqlDicionario);
    }
}
