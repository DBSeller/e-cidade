<?php

use Classes\PostgresMigration;

class M15528JetomCriaTabelaPermissao extends PostgresMigration
{
    

    public function up()
    {
        $this->adicionaDicionario();
        $this->adicionaTabela();
    }

    public function adicionaTabela()
    {

        $sql = <<<SQL
                    -- Criando  sequences
                    CREATE SEQUENCE pessoal.jetompermissao_rh251_sequencial_seq
                    INCREMENT 1
                    MINVALUE 1
                    MAXVALUE 9223372036854775807
                    START 1
                    CACHE 1;
                    
                    -- TABELAS E ESTRUTURA
                    -- Módulo: pessoal
                    CREATE TABLE pessoal.jetompermissao(
                    rh251_sequencial        int8 default 0,
                    rh251_matricula         int8 default 0,
                    rh251_comissao          int8 default 0,
                    CONSTRAINT jetompermissao_sequ_pk PRIMARY KEY (rh251_sequencial));
                    
                    -- CHAVE ESTRANGEIRA
                    ALTER TABLE pessoal.jetompermissao
                    ADD CONSTRAINT jetompermissao_matricula_fk FOREIGN KEY (rh251_matricula)
                    REFERENCES rhpessoal;
                    -- CHAVE ESTRANGEIRA
                    ALTER TABLE pessoal.jetompermissao
                    ADD CONSTRAINT jetompermissao_comissao_fk FOREIGN KEY (rh251_comissao)
                    REFERENCES jetomcomissao;

                    select configuracoes.fc_auditoria_cria_funcao('pessoal.jetompermissao');
SQL;
                $this->execute($sql);



    }

    public function adicionaDicionario ()
    {
        $sql = <<<SQL
        insert into db_sysarquivo values (1010545, 'jetompermissao', 'definição das permissões dos servidores para processar as comissões', 'rh251', '2020-04-01', 'jetom permissão', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (28,1010545);
        insert into db_syscampo values(1011166,'rh251_sequencial','int8','codigo sequencial','0', 'codigo sequencial',10,'f','f','f',1,'text','codigo sequencial');
        insert into db_syscampo values(1011167,'rh251_matricula','int8','codigo da matricula','0', 'matricula',10,'f','f','f',1,'text','matricula');
        insert into db_syscampo values(1011168,'rh251_comissao','int8','código da comissão','0', 'comissao',10,'f','f','f',1,'text','comissao');
        insert into db_sysarqcamp values(1010545,1011166,1,0);
        insert into db_sysarqcamp values(1010545,1011167,2,0);
        insert into db_sysarqcamp values(1010545,1011168,3,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010545,1011166,1,1011166);
        insert into db_sysforkey values(1010545,1011167,1,1153,0);
        insert into db_sysforkey values(1010545,1011168,1,1010487,0);
        insert into db_syssequencia values(1000897, 'jetompermissao_rh251_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000897 where codarq = 1010545 and codcam = 1011166;

SQL;
        $this->execute($sql);

    }

    public function down()
    {
        $this->removeDicionario();
        $this->removeTabela();
    }

    public function removeDicionario()
    {
        $sql = <<<SQL
                delete from db_sysarqcamp where codarq = 1010545;
                delete from db_syssequencia where codsequencia = 1000897;
                delete from db_sysforkey where codarq = 1010545;
                delete from db_sysprikey where codarq = 1010545;
                delete from db_sysarqcamp where codarq = 1010545;
                delete from db_syscampo where codcam in (1011166, 1011167, 1011168);
                delete from db_sysarqmod where codarq = 1010545;
                delete from db_sysarquivo where codarq = 1010545;
        
SQL;

        $this->execute($sql);

    }

    public function removeTabela()
    {
        $sql = <<<SQL
            DROP TABLE pessoal.jetompermissao;
            select configuracoes.fc_auditoria_remove_funcao('pessoal.jetompermissao');
            DROP SEQUENCE pessoal.jetompermissao_rh251_sequencial_seq;

SQL;
        $this->execute($sql);

    }
}
