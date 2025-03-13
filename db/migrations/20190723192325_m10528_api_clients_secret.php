<?php

use Classes\PostgresMigration;

class M10528ApiClientsSecret extends PostgresMigration
{
    public function up()
    {
        $this->criaDicionarioDados();
        $this->criaTabela();
        $this->adicionaClienteDbPref();
    }

    public function down()
    {
        $this->removeDicionarioDados();
        $this->removeClienteDbPref();
        $this->removeTabela();
    }

    private function criaDicionarioDados()
    {
        $sql = "
            INSERT INTO db_sysarquivo VALUES (1010458, 'api_clients', 'Guarda a lista de clientes que podem utilizar a API', 'db172', '2019-07-24', 'api_clients', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (7,1010458);
            INSERT INTO db_syscampo VALUES(1010621,'db172_sequencial','int4','Identificação do registro','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1010622,'db172_nome','varchar(100)','Nome do cliente','', 'Nome',100,'f','f','f',0,'text','Nome');
            INSERT INTO db_syscampo VALUES(1010623,'db172_descricao','text','Descrição','', 'Descrição',1,'f','f','f',0,'text','Descrição');
            INSERT INTO db_syscampo VALUES(1010624,'db172_chave','text','Chave','', 'Chave',1,'f','f','f',0,'text','Chave');
            INSERT INTO db_syscampo VALUES(1010625,'db172_ultima_utilizacao','date','Data última utilização','null', 'Data última utilização',10,'t','f','f',1,'text','');
            INSERT INTO db_sysarqcamp VALUES(1010458,1010621,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010458,1010622,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010458,1010623,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010458,1010624,4,0);
            INSERT INTO db_sysarqcamp VALUES(1010458,1010625,5,0);
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010458,1010621,1,1010621);
            INSERT INTO db_sysindices VALUES(1008482,'api_clients_db172_sequencial_in',1010458,'0');
            INSERT INTO db_syscadind VALUES(1008482,1010621,1);
        ";
        $this->execute($sql);
    }

    private function criaTabela()
    {
        $this->table('api_clients', array('schema' => 'configuracoes', 'id' => 'db172_sequencial'))
            ->addColumn('db172_nome', 'string', array('limit' => 100))
            ->addIndex('db172_nome', array('unique' => true))
            ->addColumn('db172_descricao', 'text')
            ->addColumn('db172_chave', 'text')
            ->addColumn('db172_ultima_utilizacao', 'datetime', array('null' => true))
            ->create();
    }

    private function adicionaClienteDbPref()
    {
        $key = sha1(md5(time() . rand(0, 9999) . 'RAND_KEY'));
        $this->execute("
            INSERT INTO configuracoes.api_clients (db172_nome, db172_descricao, db172_chave, db172_ultima_utilizacao)
            VALUES ('DBPref', 'Sistema auxiliar da prefeitura', '{$key}', null);
        ");
    }

    private function removeDicionarioDados()
    {
        $sql = "
            DELETE FROM db_sysarqcamp WHERE codarq = 1010458;
            DELETE FROM db_syscampo WHERE codcam IN (1010621, 1010622, 1010623, 1010624, 1010625);
            DELETE FROM db_sysprikey WHERE codarq = 1010458;
            DELETE FROM db_sysindices WHERE codarq = 1010458;
            DELETE FROM db_syscadind WHERE codcam = 1010621;
            DELETE FROM db_sysarqmod WHERE codarq = 1010458;
            DELETE FROM db_sysarquivo WHERE codarq = 1010458;
        ";
        $this->execute($sql);
    }

    private function removeClienteDbPref()
    {
        $this->execute("DELETE FROM configuracoes.api_clients WHERE db172_nome = 'DBPref'");
    }

    private function removeTabela()
    {
        $this->table('api_clients', array('schema' => 'configuracoes'))->drop();
    }
}
