<?php

use Classes\PostgresMigration;

class M11523UploadArquivos extends PostgresMigration
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
        $this->dicionarioDadosUp();
        $this->criarTabelas();
    }

    public function down()
    {
        $this->dicionarioDadosDown();
        $this->droparTabelas();
    }

    private function dicionarioDadosUp()
    {
        $sql = "insert into db_sysarquivo values (1010341, 'arquivocdn', 'Tabela que guarda os dados dos arquivos do cvn', '', '2018-11-27', 'Arquivo Cvn', 0, 'f', 'f', 'f', 'f' );";
        $sql .= "insert into db_sysarqmod values (7,1010341);";
        $sql .= "insert into db_syscampo values(1010102,'db59_sequencial','int8','Sequencial da tabela','0', '',1,'f','f','f',1,'text','');";
        $sql .= "insert into db_syscampo values(1010103,'j151_descricao','text','Descrição do arquivo','', '',1,'f','f','f',0,'text','');";
        $sql .= "insert into db_syscampo values(1010104,'db59_nome','varchar(1)','Nome do arquivo','', '',1,'f','f','f',0,'text','');";
        $sql .= "insert into db_sysarqarq values(0,1010341);";
        $sql .= "insert into db_sysarqcamp values(1010341,1010102,1,0);";
        $sql .= "insert into db_sysarqcamp values(1010341,1010104,2,0);";
        
        $sql .= "insert into db_syssequencia values(1000784, 'arquivocdn_db59_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);";
        $sql .= "update db_sysarqcamp set codsequencia = 1000784 where codarq = 1010341 and codcam = 1010102;";
        $sql .= "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010341,1010102,1,1010102);";
        
        $sql .= "insert into db_sysarquivo values (1010342, 'arquivocdniptubase', 'Tabela de ligação entre iptu base e arquivo cvn', '', '2018-11-27', 'Arquivo Cdn Iptu Base', 0, 'f', 'f', 'f', 'f' );";
        $sql .= "insert into db_sysarqmod values (2,1010342);";        
        $sql .= "insert into db_syscampo values(1010105,'j151_arquivocdn','int8','Campo de ligação com a tabela arrquivocvn','0', '',1,'f','f','f',1,'text','');";
        $sql .= "insert into db_syscampo values(1010106,'j151_iptubase','int8','Campo de ligação com a tabela iptubase','0', '',1,'f','f','f',1,'text','');";
        $sql .= "insert into db_sysarqcamp values(1010342,1010105,1,0);";
        $sql .= "insert into db_sysarqcamp values(1010342,1010106,2,0);";
        $sql .= "insert into db_sysarqcamp values(1010342,1010103,3,0);";
        $sql .= "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010342,1010105,1,1010105);";
        $sql .= "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010342,1010106,2,1010105);";
        $sql .= "insert into db_sysforkey values(1010342,1010105,1,1010341,0);";
        $sql .= "insert into db_sysforkey values(1010342,1010106,1,27,0);";
        $sql .= "insert into db_syscampo values(1010122,'db59_bucket','varchar(1)','Nome da bucket que se encontra o arquivo','', '',1,'f','t','f',0,'text','');";
        $sql .= "insert into db_sysarqcamp values(1010341,1010122,4,0);";
        $sql .= "insert into db_syscampo values(1010124,'db59_arquivobin','oid','Campo Bytea que salva os arquivos dos imoveis','', '',1,'f','f','f',1,'text','');";
        $sql .= "insert into db_sysarqcamp values(1010341,1010124,3,0);";

        $this->execute($sql);
    }

    private function dicionarioDadosDown()
    {        
        $sql = "DELETE from db_sysarqcamp where codcam = 1010122;";
        $sql .= "DELETE from db_syscampo where codcam = 1010122;";
        $sql .= "DELETE from db_sysarqcamp where codcam = 1010124;";
        $sql .= "DELETE from db_syscampo where codcam = 1010124;";        
        $sql .= "delete from db_sysforkey where codarq = 1010342;";
        $sql .= "delete from db_sysprikey where codarq = 1010342;";
        $sql .= "delete from db_sysarqcamp where codarq = 1010342;";
        $sql .= "delete from db_syscampo where codcam = 1010106;";
        $sql .= "delete from db_syscampo where codcam = 1010105;";
        $sql .= "delete from db_sysarqmod where codarq = 1010342;";
        $sql .= "delete from db_sysarquivo where codarq = 1010342;";
        
        $sql .= "delete from db_sysprikey where codarq = 1010341;";
        $sql .= "delete from db_sysarqarq where codarq = 1010341;";
        $sql .= "delete from db_syssequencia where codsequencia = 1000784;";
        $sql .= "delete from db_sysarqcamp where codarq = 1010341;";
        $sql .= "delete from db_syscampo where codcam = 1010102;";
        $sql .= "delete from db_syscampo where codcam = 1010103;";
        $sql .= "delete from db_syscampo where codcam = 1010104;";
        $sql .= "delete from db_sysarqmod where codarq = 1010341;";
        $sql .= "delete from db_sysarquivo where codarq = 1010341;";

        $this->execute($sql);
    }

    private function criarTabelas()
    {
        $sql = "CREATE SEQUENCE configuracoes.arquivocdn_db59_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;";
        $sql .= "Create table configuracoes.arquivocdn(
            db59_sequencial INTEGER PRIMARY KEY DEFAULT nextval('arquivocdn_db59_sequencial_seq'),
            db59_nome VARCHAR(255) NOT NULL,
            db59_bucket VARCHAR(100),
            db59_arquivobin BYTEA
        );";
        $sql .= "Create table cadastro.arquivocdniptubase(
            j151_iptubase INTEGER,
            j151_arquivocdn INTEGER,
            j151_descricao TEXT,
            PRIMARY KEY(j151_iptubase, j151_arquivocdn),
            FOREIGN KEY (j151_iptubase) REFERENCES iptubase (j01_matric),
            FOREIGN KEY (j151_arquivocdn) REFERENCES arquivocdn (db59_sequencial)
        );";

        $this->execute($sql);
    }

    private function droparTabelas()
    {
        $sql = "drop table cadastro.arquivocdniptubase;";
        $sql .= "drop table configuracoes.arquivocdn;";
        $sql .= "drop sequence configuracoes.arquivocdn_db59_sequencial_seq;";

        $this->execute($sql);
    }
}
