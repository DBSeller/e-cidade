<?php

use Classes\PostgresMigration;

class M14783TabelaComissaoJetom extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicionario();
        $this->adicionaTabela();
    }

    public function down()
    {
        $this->removeDicionario();
        $this->removeTabela();
    }

    public function adicionaDicionario()
    {
        $sql = <<<SQL
            insert into db_sysarquivo values (1010487, 'jetomcomissao', 'Comissão', 'rh242', '2019-12-10', 'Comissao', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010487);
            insert into db_syscampo values(1010828,'rh242_sequencial','int4','Código Sequencial da Comissão','0', 'Código da Comissão',10,'f','f','f',1,'text','Código da Comissão');
            insert into db_syscampo values(1010829,'rh242_descricao','varchar(100)','Descrição da comissão do Jetom','', 'Descrição',100,'f','t','f',0,'text','Descrição');
            insert into db_syscampo values(1010830,'rh242_instit','int4','Código da instituição','0', 'Código da Instituição',10,'f','f','f',1,'text','Código da Instituição');
            delete from db_sysarqcamp where codarq = 1010487;
            insert into db_sysarqcamp values(1010487,1010828,1,0);
            insert into db_sysarqcamp values(1010487,1010829,2,0);
            insert into db_sysarqcamp values(1010487,1010830,3,0);
            delete from db_sysprikey where codarq = 1010487;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010487,1010828,1,1010828);
            insert into db_syssequencia values(1000859, 'jetomcomissao_rh242_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000859 where codarq = 1010487 and codcam = 1010828;

            -- tabela configuração comissão
            insert into db_sysarquivo values (1010488, 'jetomcomissaoconfiguracao', 'Configuração da Comissão do Jetom', 'rh243', '2019-12-11', 'Jetom Comissão Configuração', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010488);
            insert into db_syscampo values(1010831,'rh243_sequencial','int4','Código da configuração da comissão','0', 'Código da Configuração',10,'f','f','f',1,'text','Código da Configuração');
            insert into db_syscampo values(1010832,'rh243_comissao','int4','Código da comissão','0', 'Código da Comissão',10,'f','f','f',1,'text','Código da Comissão');
            insert into db_syscampo values(1010833,'rh243_tiposessao','int4','Tipo da Sessão','0', 'Tipo Sessão',10,'f','f','f',1,'text','Tipo Sessão');
            insert into db_syscampo values(1010834,'rh243_rubrica','varchar(4)','Rubrica da Comissão','', 'Rubrica Comissão',4,'f','t','f',0,'text','Rubrica Comissão');
            insert into db_syscampo values(1010835,'rh243_quantidade','int4','Quantidade de Comissão','0', 'Quantidade Comissão',10,'f','f','f',1,'text','Quantidade Comissão');
            delete from db_syscampodep where codcam = 1010833;
            delete from db_syscampodef where codcam = 1010833;
            delete from db_sysarqcamp where codarq = 1010488;
            insert into db_sysarqcamp values(1010488,1010831,1,0);
            insert into db_sysarqcamp values(1010488,1010832,2,0);
            insert into db_sysarqcamp values(1010488,1010833,3,0);
            insert into db_sysarqcamp values(1010488,1010834,4,0);
            insert into db_sysarqcamp values(1010488,1010835,5,0);
            delete from db_sysprikey where codarq = 1010488;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010488,1010831,1,1010831);
            delete from db_sysforkey where codarq = 1010488 and referen = 0;
            insert into db_sysforkey values(1010488,1010832,1,1010487,0);
            delete from db_sysforkey where codarq = 1010488 and referen = 0;
            insert into db_sysforkey values(1010488,1010833,1,1010485,0);
            insert into db_syssequencia values(1000860, 'jetomcomissaoconfiguracao_rh243_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000860 where codarq = 1010488 and codcam = 1010831;

            -- Novos Campos
            insert into db_syscampo values(1010877,'rh243_funcao','int4','Código da função','0', 'Código da Função',10,'f','f','f',1,'text','Código da Função');
            insert into db_syscampo values(1010878,'rh243_valor','float8','Valor da configuração da comissão','0', 'Valor',10,'f','f','f',4,'text','Valor');
            delete from db_sysarqcamp where codarq = 1010488;
            insert into db_sysarqcamp values(1010488,1010831,1,1000860);
            insert into db_sysarqcamp values(1010488,1010832,2,0);
            insert into db_sysarqcamp values(1010488,1010833,3,0);
            insert into db_sysarqcamp values(1010488,1010834,4,0);
            insert into db_sysarqcamp values(1010488,1010835,5,0);
            insert into db_sysarqcamp values(1010488,1010877,6,0);
            insert into db_sysarqcamp values(1010488,1010878,7,0);
            delete from db_sysforkey where codarq = 1010488 and referen = 0;
            insert into db_sysforkey values(1010488,1010877,1,1010486,0);

            -- Tabela Comissão Servidor
            insert into db_sysarquivo values (1010490, 'jetomcomissaoservidor', 'Jetom Comissão do Servidor', 'rh245', '2019-12-11', 'Jetom Comissão Servidor', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010490);
            insert into db_syscampo values(1010842,'rh245_sequencial','int4','Código Sequencial da Comissão de Servidor','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
            insert into db_syscampo values(1010843,'rh245_comissao','int4','Código da Comissão do Jetom','0', 'Código da Comissão',10,'f','f','f',1,'text','Código da Comissão');
            insert into db_syscampo values(1010844,'rh245_matricula','int4','Código da Matricula/Servidor','0', 'Código Servidor',10,'f','f','f',1,'text','Código Servidor');
            insert into db_syscampo values(1010845,'rh245_mesinicio','int4','Mês Inicial','0', 'Mês Inicial',10,'f','f','f',1,'text','Mês Inicial');
            insert into db_syscampo values(1010846,'rh245_mesfim','int4','Mês Final','0', 'Mês Final',10,'f','f','f',1,'text','Mês Final');
            delete from db_syscampodep where codcam = 1010845;
            delete from db_syscampodef where codcam = 1010845;
            insert into db_syscampo values(1010847,'rh245_anoinicio','int4','Ano Inicial','0', 'Ano inicial',10,'f','f','f',1,'text','Ano inicial');
            insert into db_syscampo values(1010848,'rh245_anofim','int4','Ano Final','0', 'Ano Final',10,'f','f','f',1,'text','Ano Final');
            insert into db_syscampo values(1010849,'rh245_ativo','bool','Ativo','t', 'Ativo',1,'f','f','f',5,'text','Ativo');
            insert into db_syscampo values(1010850,'rh245_atonomeacao','text','Ato de nomeação do Servidor dentro da Comissão','', 'Ato de Nomeação',1000,'f','t','f',0,'text','Ato de Nomeação');
            insert into db_syscampo values(1010851,'rh245_documento','text','Documento de Nomeação do Servidor','', 'Documento Nomeação',1000,'t','t','f',0,'text','Documento Nomeação');
            insert into db_syscampo values(1010852,'rh245_funcao','int4','Código da função','0', 'Código da Função',10,'f','f','f',1,'text','Código da Função');
            delete from db_sysarqcamp where codarq = 1010490;
            insert into db_sysarqcamp values(1010490,1010842,1,0);
            insert into db_sysarqcamp values(1010490,1010843,2,0);
            insert into db_sysarqcamp values(1010490,1010844,3,0);
            insert into db_sysarqcamp values(1010490,1010845,4,0);
            insert into db_sysarqcamp values(1010490,1010846,5,0);
            insert into db_sysarqcamp values(1010490,1010847,6,0);
            insert into db_sysarqcamp values(1010490,1010848,7,0);
            insert into db_sysarqcamp values(1010490,1010849,8,0);
            insert into db_sysarqcamp values(1010490,1010850,9,0);
            insert into db_sysarqcamp values(1010490,1010851,10,0);
            delete from db_sysprikey where codarq = 1010490;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010490,1010842,1,1010842);
            delete from db_sysforkey where codarq = 1010490 and referen = 0;
            insert into db_sysforkey values(1010490,1010843,1,1010487,0);
            delete from db_sysforkey where codarq = 1010490 and referen = 0;
            insert into db_sysforkey values(1010490,1010844,1,1153,0);
            delete from db_sysarqcamp where codarq = 1010490;
            insert into db_sysarqcamp values(1010490,1010842,1,0);
            insert into db_sysarqcamp values(1010490,1010843,2,0);
            insert into db_sysarqcamp values(1010490,1010844,3,0);
            insert into db_sysarqcamp values(1010490,1010845,4,0);
            insert into db_sysarqcamp values(1010490,1010846,5,0);
            insert into db_sysarqcamp values(1010490,1010847,6,0);
            insert into db_sysarqcamp values(1010490,1010848,7,0);
            insert into db_sysarqcamp values(1010490,1010849,8,0);
            insert into db_sysarqcamp values(1010490,1010850,9,0);
            insert into db_sysarqcamp values(1010490,1010851,10,0);
            insert into db_sysarqcamp values(1010490,1010852,11,0);
            delete from db_sysforkey where codarq = 1010490 and referen = 0;
            insert into db_sysforkey values(1010490,1010852,1,1010486,0);
            insert into db_syssequencia values(1000862, 'jetomcomissaoservidor_rh245_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000862 where codarq = 1010490 and codcam = 1010842;

            --Tabela de configuração de funções da comissão
            insert into db_sysarquivo values (1010496, 'jetomcomissaofuncao', 'Tabela de Vinculo entre a comissão e as funções', 'rh246', '2020-01-06', 'Jetom Comissão Função', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010496);
            insert into db_syscampo values(1010879,'rh246_sequencial','int4','Código de vinculo entre comissão e funções','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1010880,'rh246_comissao','int4','Código da comissão','0', 'Código da Comissão',10,'f','f','f',1,'text','Código da Comissão');
            insert into db_syscampo values(1010881,'rh246_funcao','int4','Código da função','0', 'Código da Função',10,'f','f','f',1,'text','Código da Função');
            delete from db_sysarqcamp where codarq = 1010496;
            insert into db_sysarqcamp values(1010496,1010879,1,0);
            insert into db_sysarqcamp values(1010496,1010880,2,0);
            insert into db_sysarqcamp values(1010496,1010881,3,0);
            delete from db_sysprikey where codarq = 1010496;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010496,1010879,1,1010879);
            delete from db_sysforkey where codarq = 1010496 and referen = 0;
            insert into db_sysforkey values(1010496,1010880,1,1010487,0);
            delete from db_sysforkey where codarq = 1010496 and referen = 0;
            insert into db_sysforkey values(1010496,1010881,1,1010486,0);
            insert into db_syssequencia values(1000865, 'jetomcomissaofuncao_rh246_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000865 where codarq = 1010496 and codcam = 1010879;
            delete from db_sysarqcamp where codarq = 1010496;
            insert into db_sysarqcamp values(1010496,1010879,1,1000865);
            insert into db_sysarqcamp values(1010496,1010880,2,0);
            insert into db_sysarqcamp values(1010496,1010881,3,0);
            
SQL;

        $this->execute($sql);
    }

    public function removeDicionario()
    {
        $sql = <<<SQL
            delete from db_syssequencia where  codsequencia = 1000859;
            delete from db_sysprikey where codarq = 1010487;
            delete from db_sysarqcamp where codarq = 1010487;
            delete from db_syscampo where codcam in (1010828, 1010829, 1010830);
            delete from db_sysarqmod where codarq = 1010487;
            delete from db_sysarquivo where codarq = 1010487;

            -- delete tabela configuração
            delete from db_syssequencia where codsequencia = 1000860;
            delete from db_sysforkey where codarq = 1010488;
            delete from db_sysarqcamp where codarq = 1010488;
            delete from db_syscampodef where codcam = 1010833;
            delete from db_syscampodep where codcam = 1010833;
            delete from db_syscampo where codcam in (1010831, 1010832, 1010833, 1010834, 1010835, 1010877, 1010878);
            delete from db_sysarqmod where codarq = 1010488;
            delete from db_sysarquivo where codarq = 1010488;

            -- Tabela Comissão Servidor
            delete from db_syssequencia where codsequencia = 1000862;
            delete from db_sysforkey where codarq = 1010490;
            delete from db_sysarqcamp where codarq = 1010490;
            delete from db_syscampo where codcam in (1010842, 1010843, 1010844, 1010845, 1010846, 1010847, 1010848, 1010849, 1010850, 1010851, 1010852);
            delete from db_sysprikey where codarq = 1010490;
            delete from db_sysarqcamp where codarq = 1010490;
            delete from db_syscampodef where codcam = 1010845;
            delete from db_syscampodep where codcam = 1010845;
            delete from db_sysarqmod where codarq = 1010490;
            delete from db_sysarquivo where codarq = 1010490;

            --Tabela de configuração de funções da comissão
            delete from db_syssequencia where codsequencia = 1000865;
            delete from db_sysforkey where codarq = 1010496;
            delete from db_sysarqcamp where codarq = 1010496;
            delete from db_syscampo where codcam in (1010879, 1010880, 1010881);
            delete from db_sysprikey where codarq = 1010496;
            delete from db_sysarqmod where codarq = 1010496;
            delete from db_sysarquivo where codarq =1010496;

SQL;

        $this->execute($sql);
    }

    public function adicionaTabela()
    {
        $sql = <<<SQL
            CREATE SEQUENCE pessoal.jetomcomissao_rh242_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            CREATE TABLE pessoal.jetomcomissao(
                rh242_sequencial int4 NOT NULL default nextval('jetomcomissao_rh242_sequencial_seq'),
                rh242_descricao	 varchar(100) NOT NULL ,
                rh242_instit int4 default 0,
                CONSTRAINT jetomcomissao_sequ_pk PRIMARY KEY (rh242_sequencial)
            );

            select configuracoes.fc_auditoria_cria_funcao('pessoal.jetomcomissao');

            -- Tabela Comissão Configuração

            CREATE SEQUENCE pessoal.jetomcomissaoconfiguracao_rh243_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            CREATE TABLE pessoal.jetomcomissaoconfiguracao(
                rh243_sequencial int4 NOT NULL default nextval('jetomcomissaoconfiguracao_rh243_sequencial_seq'),
                rh243_comissao int4 NOT NULL default 0,
                rh243_funcao int4 NOT NULL default 0,
                rh243_tiposessao int4 NOT NULL default 0,
                rh243_rubrica varchar(4) NOT NULL ,
                rh243_valor float8 NOT NULL default 0,
                CONSTRAINT jetomcomissaoconfiguracao_sequ_pk PRIMARY KEY (rh243_sequencial)
            );
            ALTER TABLE pessoal.jetomcomissaoconfiguracao
                ADD CONSTRAINT jetomcomissaoconfiguracao_comissao_fk FOREIGN KEY (rh243_comissao)
                REFERENCES pessoal.jetomcomissao;

            ALTER TABLE pessoal.jetomcomissaoconfiguracao
                ADD CONSTRAINT jetomcomissaoconfiguracao_funcao_fk FOREIGN KEY (rh243_funcao)
                REFERENCES pessoal.jetomfuncao;

            ALTER TABLE pessoal.jetomcomissaoconfiguracao
                ADD CONSTRAINT jetomcomissaoconfiguracao_tiposessao_fk FOREIGN KEY (rh243_tiposessao)
                REFERENCES pessoal.jetomtiposessao;

            select configuracoes.fc_auditoria_cria_funcao('pessoal.jetomcomissaoconfiguracao');

            -- Tabela Comissão Servidor
            CREATE SEQUENCE pessoal.jetomcomissaoservidor_rh245_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;
                

            CREATE TABLE pessoal.jetomcomissaoservidor(
                rh245_sequencial int4 NOT NULL default nextval('jetomcomissaoservidor_rh245_sequencial_seq'),
                rh245_comissao int4 NOT NULL default 0,
                rh245_matricula int4 NOT NULL default 0,
                rh245_mesinicio int4 NOT NULL default 0,
                rh245_mesfim int4 NOT NULL default 0,
                rh245_anoinicio int4 NOT NULL default 0,
                rh245_anofim int4 NOT NULL default 0,
                rh245_ativo bool NOT NULL default 't',
                rh245_atonomeacao text,
                rh245_documento text,
                rh245_funcao int4 NOT NULL,
                CONSTRAINT jetomcomissaoservidor_sequ_pk PRIMARY KEY (rh245_sequencial)
            );

            ALTER TABLE pessoal.jetomcomissaoservidor
            ADD CONSTRAINT jetomcomissaoservidor_comissao_fk FOREIGN KEY (rh245_comissao)
            REFERENCES jetomcomissao;
            
            ALTER TABLE pessoal.jetomcomissaoservidor
            ADD CONSTRAINT jetomcomissaoservidor_rhpessoal_fk FOREIGN KEY (rh245_matricula)
            REFERENCES rhpessoal;
            
           ALTER TABLE pessoal.jetomcomissaoservidor
           ADD CONSTRAINT jetomcomissaoservidor_jetomfuncao_fk FOREIGN KEY (rh245_funcao)
           REFERENCES jetomfuncao;
            
            select configuracoes.fc_auditoria_cria_funcao('pessoal.jetomcomissaoservidor');

            -- Tabela Jetom Comissao Funcao
            -- Criando  sequences
            CREATE SEQUENCE pessoal.jetomcomissaofuncao_rh246_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            -- TABELAS E ESTRUTURA

            -- Módulo: pessoal
            CREATE TABLE pessoal.jetomcomissaofuncao(
                rh246_sequencial int4 NOT NULL default nextval('jetomcomissaofuncao_rh246_sequencial_seq'),
                rh246_comissao int4 NOT NULL default 0,
                rh246_funcao int4 NOT NULL default 0,
                rh246_quantidade int4 default 0,
            CONSTRAINT jetomcomissaofuncao_sequ_pk PRIMARY KEY (rh246_sequencial));

            -- CHAVE ESTRANGEIRA
            ALTER TABLE jetomcomissaofuncao
                ADD CONSTRAINT jetomcomissaofuncao_comissao_fk FOREIGN KEY (rh246_comissao)
                REFERENCES jetomcomissao;

            ALTER TABLE jetomcomissaofuncao
                ADD CONSTRAINT jetomcomissaofuncao_funcao_fk FOREIGN KEY (rh246_funcao)
                REFERENCES jetomfuncao;

            select configuracoes.fc_auditoria_cria_funcao('pessoal.jetomcomissaofuncao');

SQL;

        $this->execute($sql);
    }

    public function removeTabela()
    {
        $sql = <<<SQL
            -- Tabela Comissão Servidor
            DROP TABLE pessoal.jetomcomissaoservidor;
            DROP SEQUENCE pessoal.jetomcomissaoservidor_rh245_sequencial_seq;

            -- Tabela Configuração comissão
            DROP TABLE pessoal.jetomcomissaoconfiguracao;
            DROP SEQUENCE pessoal.jetomcomissaoconfiguracao_rh243_sequencial_seq;

            select configuracoes.fc_auditoria_remove_funcao('pessoal.jetomcomissaoconfiguracao');

            -- Tabela Comissão Funcao
            DROP TABLE pessoal.jetomcomissaofuncao;
            DROP SEQUENCE pessoal.jetomcomissaofuncao_rh246_sequencial_seq;

            select configuracoes.fc_auditoria_remove_funcao('pessoal.jetomcomissaofuncao');

            -- Tabela Comissão
            DROP TABLE pessoal.jetomcomissao;
            DROP SEQUENCE pessoal.jetomcomissao_rh242_sequencial_seq;

            select configuracoes.fc_auditoria_remove_funcao('pessoal.jetomcomissao');
SQL;

        $this->execute($sql);
    }
}
