<?php

use Classes\PostgresMigration;

class M12819ContasContabilBancos extends PostgresMigration
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

        $this->upDicionarioDados();
        $this->upDDL();
    }

    protected function upDicionarioDados()
    {

        $this->execute("insert into db_sysarquivo values (1010419, 'bancovinculocontatipo', 'Tipos de vinculos de contas', 'db59', '2019-02-12', 'Tipos de vinculos de contas', 0, 'f', 'f', 'f', 'f' );");
        $this->execute("insert into db_sysarqmod values (7,1010419);");

        $this->execute("insert into db_sysarquivo values (1010420, 'bancovinculoconta', 'Vinculo de Contas Bancarias com contabilidade', 'db502', '2019-02-12', 'Vinculo de Contas Bancarias com contabilidade', 0, 'f', 'f', 'f', 'f' );");
        $this->execute("insert into db_sysarqmod values (7,1010420);");


        /**
         * CAMPOS
         */
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010329 ,'db501_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010330 ,'db501_descricao' ,'varchar(100)' ,'Descrição' ,'' ,'Descrição' ,100 ,'false' ,'true' ,'false' ,0 ,'text' ,'Descrição' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010419 ,1010329 ,1 ,0 );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010419 ,1010330 ,2 ,0 );");

        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010331 ,'db502_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010332 ,'db502_db_bancos' ,'varchar(10)' ,'Código do Banco' ,'' ,'Código do Banco' ,10 ,'false' ,'true' ,'false' ,0 ,'text' ,'Código do Banco' );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010333 ,'db502_reduz' ,'int4' ,'Código do Banco' ,'' ,'Codigo Reduzido' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Codigo Reduzido' );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010334 ,'db502_bancovinculocontatipo' ,'int4' ,'Tipo do Vinculo' ,'' ,'Tipo do Vinculo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo do Vinculo' );");

        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010420 ,1010331 ,1 ,0 );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010420 ,1010332 ,2 ,0 );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010420 ,1010333 ,3 ,0 );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010420 ,1010334 ,4 ,0 );");

        $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010419,1010329,1,1010330);");
        $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010420,1010331,1,1010333);");
        $this->execute("insert into db_sysindices values(1008430,'bancovinculoconta_banco_in',1010420,'0');");
        $this->execute("insert into db_syscadind values(1008430,1010332,1);");
        $this->execute("insert into db_sysindices values(1008431,'bancovinculoconta_reduz_in',1010420,'0');");
        $this->execute("insert into db_syscadind values(1008431,1010333,1);;");
        $this->execute("insert into db_sysforkey values(1010420,1010332,1,1185,0);");
        $this->execute("insert into db_sysforkey values(1010420,1010334,1,1010419,0);");
        $this->execute("insert into db_syssequencia values(1000816, 'bancovinculoconta_db502_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
        $this->execute("update db_sysarqcamp set codsequencia = 1000816 where codarq = 1010420 and codcam = 1010331;");
    }

    public function down()
    {

        $this->downDicionarioDados();
        $this->downDDL();

    }

    protected function downDicionarioDados()
    {

        $this->execute("delete from db_sysforkey where codarq in (1010419, 1010420);");
        $this->execute("delete from db_syssequencia where codsequencia in (1000816);");
        $this->execute("delete from db_sysindices where codind in (1008430, 1008431);");
        $this->execute("delete from db_syscadind where codind in (1008430, 1008431);");
        $this->execute("delete from db_sysprikey where codarq in (1010419, 1010420);");
        $this->execute("delete from db_sysarqcamp where codarq IN (1010419,1010420);");
        $this->execute("delete from db_syscampo where codcam in(1010329, 1010330, 1010331, 1010332, 1010333, 1010334)");
        $this->execute("delete from db_sysarqmod where codarq in(1010419, 1010420)");
        $this->execute("delete from db_sysarquivo where codarq in(1010419, 1010420)");
    }

    protected function upDDL()
    {

        $this->execute("CREATE SEQUENCE configuracoes.bancovinculoconta_db502_sequencial_seq
                            INCREMENT 1
                            MINVALUE 1
                            MAXVALUE 9223372036854775807
                            START 1
                            CACHE 1"
        );


        $this->execute("create table configuracoes.bancovinculocontatipo (
                         db501_sequencial integer,
                         db501_descricao varchar,
                         CONSTRAINT bancovinculocontatipo_sequ_pk PRIMARY KEY (db501_sequencial)
                      );"
        );

        $this->execute("CREATE TABLE configuracoes.bancovinculoconta(
                            db502_sequencial                int4  default nextval('bancovinculoconta_db502_sequencial_seq'),
                            db502_db_bancos  varchar not null,
                            db502_reduz integer not null,
                            db502_bancovinculocontatipo integer,
                            CONSTRAINT bancovinculoconta_sequ_pk PRIMARY KEY (db502_sequencial));"
        );

        $this->execute("ALTER TABLE configuracoes.bancovinculoconta
                                ADD CONSTRAINT bancovinculoconta_bancos_fk FOREIGN KEY (db502_db_bancos)
                                REFERENCES db_bancos;
                             "
        );
        $this->execute("ALTER TABLE configuracoes.bancovinculoconta
                                ADD CONSTRAINT bancovinculoconta_bancovinculocontatipo_fk FOREIGN KEY (db502_bancovinculocontatipo)
                                REFERENCES configuracoes.bancovinculocontatipo;"
        );

        $this->execute("CREATE  INDEX bancovinculoconta_banco_in ON configuracoes.bancovinculoconta(db502_db_bancos);");
        $this->execute("CREATE  INDEX bancovinculoconta_reduz_in ON configuracoes.bancovinculoconta(db502_reduz);");

        $this->execute("insert into configuracoes.bancovinculocontatipo VALUES (1, 'CONTAS DE APLICAÇÃO');");
        $this->execute("insert into configuracoes.bancovinculocontatipo VALUES (2, 'CONTAS DE MOVIMENTAÇÃO');");
        $this->execute("insert into configuracoes.bancovinculocontatipo VALUES (3, 'CONTAS VINCULADAS');");
        $this->execute("insert into configuracoes.bancovinculocontatipo VALUES (4, 'OUTRAS CONTAS');");
    }

    protected function downDDL()
    {
        $this->execute("drop table if exists configuracoes.bancovinculoconta");
        $this->execute("drop table if exists configuracoes.bancovinculocontatipo");
        $this->execute("DROP SEQUENCE IF EXISTS configuracoes.bancovinculoconta_db502_sequencial_seq;");
    }
}
