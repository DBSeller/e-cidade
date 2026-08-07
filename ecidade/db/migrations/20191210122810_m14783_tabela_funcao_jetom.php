<?php

use Classes\PostgresMigration;

class M14783TabelaFuncaoJetom extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicionario();
        $this->adicionaTabela();
    }

    public function adicionaTabela()
    {
        $sql = "CREATE SEQUENCE pessoal.jetomfuncao_rh241_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

                CREATE TABLE pessoal.jetomfuncao(
                rh241_sequencial int4 not null default nextval('pessoal.jetomfuncao_rh241_sequencial_seq'),
                rh241_instit		int4 NOT NULL default 0,
                rh241_descricao		varchar(100) ,
                CONSTRAINT jetomfuncao_sequ_pk PRIMARY KEY (rh241_sequencial));

                select configuracoes.fc_auditoria_cria_funcao('pessoal.jetomfuncao');
                ";

        $this->execute($sql);
    }

    public function removeTabela()
    {
        $sql = "DROP TABLE pessoal.jetomfuncao;
                select configuracoes.fc_auditoria_remove_funcao('pessoal.jetomfuncao');
                DROP SEQUENCE pessoal.jetomfuncao_rh241_sequencial_seq;
                ";

        $this->execute($sql);
    }

    public function adicionaDicionario()
    {
        $sql = "insert into db_sysarquivo values (1010486, 'jetomfuncao', 'Descrição de função dentro da comissão do Jetom.', 'rh241', '2019-12-10', 'Funcao', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010486);
            insert into db_syscampo values(1010825,'rh241_sequencial','int4','Código sequêncial da função do Jetom.','0', 'Código da Função',10,'f','f','f',1,'text','Código da Função');
            insert into db_syscampo values(1010826,'rh241_instit','int4','Código da Instituição.','0', 'Código da Instituição',10,'f','f','f',1,'text','Código da Instituição');
            insert into db_syscampo values(1010827,'rh241_descricao','varchar(100)','Descrição da função.','', 'Descrição',100,'f','t','f',0,'text','Descrição');
            delete from db_sysprikey where codarq = 1010486;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010486,1010825,1,1010825);
            insert into db_syssequencia values(1000858, 'jetomfuncao_rh241_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000858 where codarq = 1010486 and codcam = 1010825;
            delete from db_sysarqcamp where codarq = 1010486;
            insert into db_sysarqcamp values(1010486,1010825,1,1000858);
            insert into db_sysarqcamp values(1010486,1010826,2,0);
            insert into db_sysarqcamp values(1010486,1010827,3,0);";

        $this->execute($sql);
    }

    public function removeDicionario()
    {
        $sql = "delete from db_sysarqcamp where codarq = 1010486;
                delete from db_syssequencia where codsequencia = 1000858;
                delete from db_sysprikey where codarq = 1010486;
                delete from db_syscampo where codcam in (1010825, 1010826, 1010827);
                delete from db_sysarqmod where codarq = 1010486;
                delete from db_sysarquivo where codarq = 1010486;";

        $this->execute($sql);
    }

    public function down()
    {
        $this->removeDicionario();
        $this->removeTabela();
    }
}
