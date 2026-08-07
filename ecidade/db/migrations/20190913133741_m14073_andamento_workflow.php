<?php

use Classes\PostgresMigration;

class M14073AndamentoWorkflow extends PostgresMigration
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
        $this->upDicionario();
        $this->upTables();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downTables();
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL

            insert into db_sysarquivo values (1010468, 'transicao', 'Tabela que guarda as transições de atividades', 'db174', '2019-09-13', 'Transição', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (7,1010468);
            insert into db_sysarquivo values (1010469, 'transicaoacao', 'Tabela que liga uma transição a uma ação', 'db175', '2019-09-13', 'Transição Ação', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (7,1010469);
            insert into db_sysarquivo values (1010470, 'acao', 'Tabela que guarda as ações disponiveis', 'db176', '2019-09-13', 'Ação', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (7,1010470);
            insert into db_syscampo values(1010740,'db174_sequencial','int4','Sequencial da tabela','0', 'Sequencial',1,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010741,'db174_origem','int4','Campo que guarda a atividade de origem','0', 'Origem',1,'f','f','f',1,'text','Origem');
            insert into db_syscampo values(1010742,'db174_destino','int4','Campo que guarda a atividade de destino','0', 'Destino',1,'f','f','f',1,'text','Destino');
            insert into db_syscampo values(1010743,'db175_sequencial','int4','Sequencial da tabela','0', 'Sequencial',1,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010744,'db175_transicao','int4','Campo que guarda referencia para a transição','0', 'Transição',1,'f','f','f',1,'text','Transição');
            insert into db_syscampo values(1010745,'db175_acao','int4','Campo que guarda referência para ação','0', 'Ação',1,'f','f','f',1,'text','Ação');
            insert into db_syscampo values(1010746,'db176_sequencial','int4','Sequencial da tabela','0', 'Sequencial',1,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1010747,'db176_descricao','varchar(100)','Descrição da ação','', 'Descricao',100,'f','t','f',0,'text','Descricao');
            delete from db_sysarqcamp where codarq = 1010468;
            insert into db_sysarqcamp values(1010468,1010740,1,0);
            insert into db_sysarqcamp values(1010468,1010741,2,0);
            insert into db_sysarqcamp values(1010468,1010742,3,0);
            delete from db_sysprikey where codarq = 1010468;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010468,1010740,1,1010740);
            delete from db_sysforkey where codarq = 1010468 and referen = 0;
            insert into db_sysforkey values(1010468,1010741,1,3158,0);
            insert into db_sysforkey values(1010468,1010742,1,3158,0);
            delete from db_sysarqcamp where codarq = 1010470;
            insert into db_sysarqcamp values(1010470,1010746,1,0);
            insert into db_sysarqcamp values(1010470,1010747,2,0);
            delete from db_sysprikey where codarq = 1010470;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010470,1010746,1,1010746);
            delete from db_sysarqcamp where codarq = 1010469;
            insert into db_sysarqcamp values(1010469,1010743,1,0);
            insert into db_sysarqcamp values(1010469,1010744,2,0);
            insert into db_sysarqcamp values(1010469,1010745,3,0);
            delete from db_sysprikey where codarq = 1010469;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010469,1010743,1,1010743);
            delete from db_sysforkey where codarq = 1010469 and referen = 0;
            insert into db_sysforkey values(1010469,1010744,1,1010468,0);
            insert into db_sysforkey values(1010469,1010745,1,1010470,0);

SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL

            DELETE FROM db_sysforkey where codarq in (1010468, 1010470, 1010469);
            DELETE FROM db_sysprikey where codarq in (1010468, 1010470, 1010469);
            DELETE FROM db_sysarqcamp where codarq in (1010468, 1010470, 1010469);
            DELETE FROM db_syscampo where codcam in (1010740, 1010741, 1010742, 1010743, 1010744, 1010745, 1010746, 1010747);
            DELETE FROM db_sysarqmod where codarq in (1010468, 1010470, 1010469);
            DELETE FROM db_sysarquivo where codarq in (1010468, 1010470, 1010469);

SQL
        );
    }

    public function upTables()
    {
        $this->execute(<<<SQL

            CREATE SEQUENCE configuracoes.transicao_db174_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE configuracoes.transicao(

                db174_sequencial integer NOT NULL,
                db174_origem integer NOT NULL,
                db174_destino integer NOT NULL,
                PRIMARY KEY (db174_sequencial)

            );

            CREATE SEQUENCE configuracoes.acao_db176_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE configuracoes.acao(

                db176_sequencial integer NOT NULL,
                db176_descricao varchar(100) NOT NULL,
                PRIMARY KEY (db176_sequencial)

            );

            CREATE SEQUENCE configuracoes.transicaoacao_db175_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE configuracoes.transicaoacao(

                db175_sequencial integer NOT NULL,
                db175_transicao integer NOT NULL,
                db175_acao integer NOT NULL,
                PRIMARY KEY (db175_sequencial)

            );

            ALTER TABLE transicaoacao
              ADD CONSTRAINT transicaoacao_transicao_fk FOREIGN KEY (db175_transicao)
       REFERENCES transicao;

            ALTER TABLE transicaoacao
              ADD CONSTRAINT transicaoacao_acao_fk FOREIGN KEY (db175_acao)
       REFERENCES acao;

SQL
        );
    }

    public function downTables()
    {
        $this->execute(<<<SQL

            DROP SEQUENCE transicao_db174_sequencial_seq;
            DROP SEQUENCE acao_db176_sequencial_seq;
            DROP SEQUENCE transicaoacao_db175_sequencial_seq;

            DROP TABLE configuracoes.transicaoacao;
            DROP TABLE configuracoes.acao;
            DROP TABLE configuracoes.transicao;

SQL
        );
    }
}
