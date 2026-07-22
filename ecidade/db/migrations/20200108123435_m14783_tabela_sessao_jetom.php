<?php

use Classes\PostgresMigration;

class M14783TabelaSessaoJetom extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicionario();
        $this->adicionaTabela();
    }

    public function adicionaDicionario()
    {
        $sql = <<<SQL
                -- JETOM SESSAO --
                insert into db_sysarquivo values (1010497, 'jetomsessao', 'Tabela de sessao do jetom.', 'rh247', '2020-01-08', 'Jetom Sessao', 0, 'f', 'f', 'f', 'f' );
                insert into db_sysarqmod values (28,1010497);
                insert into db_syscampo values(1010885,'rh247_sequencial','int4','Código Sequencial da tabela jetom sessão','0', 'Código',10,'f','f','f',1,'text','Código');
                insert into db_syscampo values(1010886,'rh247_comissao','int4','Código da Comissão.','0', 'Codigo Comissao',10,'f','f','f',1,'text','Codigo Comissao');
                insert into db_syscampo values(1010887,'rh247_data','date','Data em que ocorre a sessão.','null', 'Data da Sessao',10,'f','f','f',1,'text','Data da Sessao');
                insert into db_syscampo values(1010888,'rh247_processada','bool','Define o estado de processamento da sessão.','false', 'Processada',1,'f','f','f',5,'text','Processada');
                insert into db_syscampo values(1010889,'rh247_tiposessao','int4','Tipo da sessão.','0', 'Tipo Sessao',10,'f','f','f',1,'text','Tipo Sessao');
                delete from db_sysarqcamp where codarq = 1010497;
                insert into db_sysarqcamp values(1010497,1010885,1,0);
                insert into db_sysarqcamp values(1010497,1010886,2,0);
                insert into db_sysarqcamp values(1010497,1010887,3,0);
                insert into db_sysarqcamp values(1010497,1010888,4,0);
                insert into db_sysarqcamp values(1010497,1010889,5,0);
                delete from db_sysprikey where codarq = 1010497;
                delete from db_sysprikey where codarq = 1010497;
                insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010497,1010885,1,1010885);
                delete from db_sysforkey where codarq = 1010497 and referen = 0;
                insert into db_sysforkey values(1010497,1010886,1,1010497,0);
                delete from db_sysarqcamp where codarq = 1010497;
                insert into db_sysarqcamp values(1010497,1010885,1,0);
                insert into db_sysarqcamp values(1010497,1010886,2,0);
                insert into db_sysarqcamp values(1010497,1010887,3,0);
                insert into db_sysarqcamp values(1010497,1010888,4,0);
                insert into db_sysarqcamp values(1010497,1010889,5,0);
                delete from db_sysforkey where codarq = 1010497 and referen = 0;
                insert into db_sysforkey values(1010497,1010886,1,1010487,0);
                delete from db_sysforkey where codarq = 1010497 and referen = 1010497;
                delete from db_sysforkey where codarq = 1010497 and referen = 0;
                insert into db_sysforkey values(1010497,1010889,1,1010485,0);
                insert into db_syssequencia values(1000866, 'jetomsessao_rh247_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
                update db_sysarqcamp set codsequencia = 1000866 where codarq = 1010497 and codcam = 1010885;
                delete from db_sysarqcamp where codarq = 1010497;
                insert into db_sysarqcamp values(1010497,1010885,1,1000866);
                insert into db_sysarqcamp values(1010497,1010886,2,0);
                insert into db_sysarqcamp values(1010497,1010887,3,0);
                insert into db_sysarqcamp values(1010497,1010888,4,0);
                insert into db_sysarqcamp values(1010497,1010889,5,0);





                -- JETOM SERVIDOR --
                insert into db_sysarquivo values (1010498, 'jetomsessaoservidor', 'Sessão de servidores do jetom.', 'rh248', '2020-01-08', 'Jetom Sessao Servidor', 0, 'f', 'f', 'f', 'f' );
                insert into db_sysarqmod values (28,1010498);

                insert into db_syscampo values(1010890,'rh248_sequencial','int4','Código do servidor na sessão. ','0', 'Codigo Servidor Sessao',10,'f','f','f',1,'text','Codigo Servidor Sessao');
                insert into db_syscampo values(1010891,'rh248_sessao','int4','Código da sessão do servidor.','0', 'Codigo Sessao',10,'f','f','f',1,'text','Codigo Sessao');
                insert into db_syscampo values(1010892,'rh248_servidor','int4','Código do servidor.','0', 'Codigo Servidor',10,'f','f','f',1,'text','Codigo Servidor');


                delete from db_sysarqcamp where codarq = 1010498;
                insert into db_sysarqcamp values(1010498,1010890,1,0);
                insert into db_sysarqcamp values(1010498,1010891,2,0);
                insert into db_sysarqcamp values(1010498,1010892,3,0);

                delete from db_sysprikey where codarq = 1010498;
                insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010498,1010890,1,1010890);


                delete from db_sysforkey where codarq = 1010498 and referen = 0;
                insert into db_sysforkey values(1010498,1010891,1,1010485,0);
                delete from db_sysforkey where codarq = 1010498 and referen = 0;
                insert into db_sysforkey values(1010498,1010892,1,1010490,0);
                delete from db_sysforkey where codarq = 1010498 and referen = 1010485;
                delete from db_sysforkey where codarq = 1010498 and referen = 0;
                insert into db_sysforkey values(1010498,1010891,1,1010497,0);


                delete from db_sysarqcamp where codarq = 1010498;
                insert into db_sysarqcamp values(1010498,1010890,1,0);
                insert into db_sysarqcamp values(1010498,1010891,2,0);
                insert into db_sysarqcamp values(1010498,1010892,3,0);
                update db_sysarqcamp set codsequencia = 0 where codsequencia = 0;
                delete from db_syssequencia where codsequencia = 0;
                delete from db_sysarqcamp where codarq = 1010498;
                insert into db_sysarqcamp values(1010498,1010890,1,0);
                insert into db_sysarqcamp values(1010498,1010891,2,0);
                insert into db_sysarqcamp values(1010498,1010892,3,0);
                insert into db_syssequencia values(1000867, 'jetomsessaoservidor_rh248_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
                update db_sysarqcamp set codsequencia = 1000867 where codarq = 1010498 and codcam = 1010890;
SQL;
        $this->execute($sql);
    }

    public function removeDicionario()
    {
        $sql = <<<SQL
                --JETOM SESSAO--
                delete from db_syssequencia where codsequencia = 1000866;
                delete from db_sysforkey where codarq = 1010497;
                delete from db_sysarqcamp where codarq = 1010497;
                delete from db_sysforkey where codarq = 1010497;
                delete from db_sysprikey where codarq = 1010497;
                delete from db_sysarqcamp where codarq = 1010497;
                delete from db_syscampo where codcam in (1010885, 1010886, 1010887, 1010888, 1010889);
                delete from db_sysarqmod where codarq = 1010497;
                delete from db_sysarquivo where codarq = 1010497;

                -- JETOM SERVIDOR
                delete from db_syssequencia where codsequencia = 1000867;
                delete from db_sysforkey where codarq = 1010498;
                delete from db_sysarqcamp where codarq = 1010498;
                delete from db_sysforkey where codarq = 1010498;
                delete from db_sysprikey where codarq = 1010498;
                delete from db_sysarqcamp where codarq = 1010498;
                delete from db_syscampo where codcam in (1010890, 1010891, 1010892);
                delete from db_sysarqmod where codarq = 1010498;
                delete from db_sysarquivo where codarq = 1010498;




SQL;
        $this->execute($sql);

    }

    public function adicionaTabela()
    {
        $sql = <<<SQL

              -- JETOM SESSAO --
              CREATE SEQUENCE pessoal.jetomsessao_rh247_sequencial_seq
                    INCREMENT 1
                    MINVALUE 1
                    MAXVALUE 9223372036854775807
                    START 1
                    CACHE 1;

              CREATE TABLE pessoal.jetomsessao(
                    rh247_sequencial serial NOT NULL,
                    rh247_comissao int4 NOT NULL default 0,
                    rh247_data date,
                    rh247_processada boolean default false,
                    rh247_tiposessao int4 NOT NULL default 0,

                    CONSTRAINT jetomsessao_sequ_pk PRIMARY KEY (rh247_sequencial)
              );

              ALTER TABLE pessoal.jetomsessao
              ADD CONSTRAINT jetomsessao_comissao_fk FOREIGN KEY (rh247_comissao)
              REFERENCES jetomcomissao;

              ALTER TABLE pessoal.jetomsessao
              ADD CONSTRAINT jetomsessao_tiposessao_fk FOREIGN KEY (rh247_tiposessao)
              REFERENCES jetomtiposessao;

              select configuracoes.fc_auditoria_cria_funcao('pessoal.jetomsessao');

              -- JETOM SESSAO SERVIDOR --
              CREATE SEQUENCE pessoal.jetomsessaoservidor_rh248_sequencial_seq
                    INCREMENT 1
                    MINVALUE 1
                    MAXVALUE 9223372036854775807
                    START 1
                    CACHE 1;

                    CREATE TABLE pessoal.jetomsessaoservidor(
                            rh248_sequencial serial NOT NULL,
                            rh248_sessao int4 NOT NULL,
                            rh248_servidor int4 NOT NULL,
                            CONSTRAINT jetomsessaoservidor_sequ_pk PRIMARY KEY (rh248_sequencial)
                    );

                    ALTER TABLE pessoal.jetomsessaoservidor
                    ADD CONSTRAINT jetomsessaoservidor_servidor_fk FOREIGN KEY (rh248_servidor)
                    REFERENCES jetomcomissaoservidor;

                    ALTER TABLE pessoal.jetomsessaoservidor
                    ADD CONSTRAINT jetomsessaoservidor_sessao_fk FOREIGN KEY (rh248_sessao)
                    REFERENCES jetomsessao;

              select configuracoes.fc_auditoria_cria_funcao('pessoal.jetomsessaoservidor');

SQL;
        $this->execute($sql);

    }

    public function removeTabela()
    {
        $sql = <<<SQL
                -- JETOM SESSAO SERVIDOR --
                DROP TABLE pessoal.jetomsessaoservidor;
                select configuracoes.fc_auditoria_remove_funcao('pessoal.jetomsessaoservidor');
                DROP SEQUENCE pessoal.jetomsessaoservidor_rh248_sequencial_seq;


                -- JETOM SESSAO --
                DROP TABLE pessoal.jetomsessao;
                select configuracoes.fc_auditoria_remove_funcao('pessoal.jetomsessao');
                DROP SEQUENCE pessoal.jetomsessao_rh247_sequencial_seq;


SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $this->removeDicionario();
        $this->removeTabela();
    }
}
