<?php

use Classes\PostgresMigration;

class M14783TabelaJetomTipoSessao extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicionario();
        $this->adicionaTabela();
    }

    public function adicionaDicionario()
    {
        $sql = <<<SQL
        insert into db_sysarquivo values (1010499, 'jetomcomissaotiposessao', 'Jetom comissao e o seu tipo de sessao.', 'rh249', '2020-01-10', 'Tipo Sessao da Comissao', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (28,1010499);
        insert into db_syscampo values(1010893,'rh249_sequencial','int4','Código da comissão tipo sessão.','0', 'Codigo Comissao Tipo Sessao',10,'f','f','f',1,'text','Codigo Comissao Tipo Sessao');
        insert into db_syscampo values(1010894,'rh249_comissao','int4','Código da comissão do jetom.','0', 'Codigo Comissao',10,'f','f','f',1,'text','Codigo Comissao');
        insert into db_syscampo values(1010895,'rh249_tiposessao','int4','Código do tipo da sessão.','0', 'Codigo Tipo Sessao',10,'f','f','f',1,'text','Codigo Tipo Sessao');
        insert into db_syscampo values(1010896,'rh249_quantidade','int4','Quantidade de Comissão.','0', 'Quantidade',10,'f','f','f',1,'text','Quantidade');
        delete from db_sysarqcamp where codarq = 1010499;
        insert into db_sysarqcamp values(1010499,1010893,1,0);
        insert into db_sysarqcamp values(1010499,1010894,2,0);
        insert into db_sysarqcamp values(1010499,1010895,3,0);
        insert into db_sysarqcamp values(1010499,1010896,4,0);
        delete from db_sysprikey where codarq = 1010499;
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010499,1010893,1,1010893);
        delete from db_sysforkey where codarq = 1010499 and referen = 0;
        insert into db_sysforkey values(1010499,1010894,1,1010487,0);
        delete from db_sysforkey where codarq = 1010499 and referen = 0;
        insert into db_sysforkey values(1010499,1010895,1,1010485,0);
        insert into db_syssequencia values(1000868, 'jetomcomissaotiposessao_rh249_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000868 where codarq = 1010499 and codcam = 1010893;
        delete from db_sysarqcamp where codarq = 1010499;
        insert into db_sysarqcamp values(1010499,1010893,1,1000868);
        insert into db_sysarqcamp values(1010499,1010894,2,0);
        insert into db_sysarqcamp values(1010499,1010895,3,0);
        insert into db_sysarqcamp values(1010499,1010896,4,0);

SQL;
        $this->execute($sql);

    }

    public function removeDicionario()
    {
        $sql = <<<SQL
                delete from db_sysarqcamp where codarq = 1010499;
                delete from db_syssequencia where codsequencia = 1000868;
                delete from db_sysforkey where codarq = 1010499;
                delete from db_sysprikey where codarq = 1010499;
                delete from db_sysarqcamp where codarq = 1010499;
                delete from db_syscampo where codcam in (1010893, 1010894, 1010895, 1010896);
                delete from db_sysarqmod where codarq = 1010499;
                delete from db_sysarquivo where codarq = 1010499;
        
SQL;

        $this->execute($sql);

    }

    public function adicionaTabela()
    {
        $sql = <<<SQL
            CREATE SEQUENCE pessoal.jetomcomissaotiposessao_rh249_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            CREATE TABLE pessoal.jetomcomissaotiposessao(
            rh249_sequencial		 serial NOT NULL,
            rh249_comissao int4 NOT NULL default 0,
            rh249_tiposessao int4 NOT NULL default 0,
            rh249_quantidade int4,
            CONSTRAINT jetomcomissaotiposessao_sequ_pk PRIMARY KEY (rh249_sequencial));
            
            ALTER TABLE pessoal.jetomcomissaotiposessao
            ADD CONSTRAINT jetomcomissaotiposessao_comissao_fk FOREIGN KEY (rh249_comissao)
            REFERENCES jetomcomissao;
            
            ALTER TABLE pessoal.jetomcomissaotiposessao
            ADD CONSTRAINT jetomcomissaotiposessao_tiposessao_fk FOREIGN KEY (rh249_tiposessao)
            REFERENCES jetomtiposessao;
            
            select configuracoes.fc_auditoria_cria_funcao('pessoal.jetomcomissaotiposessao');
SQL;
        $this->execute($sql);

    }

    public function removeTabela()
    {
        $sql = <<<SQL
            DROP TABLE pessoal.jetomcomissaotiposessao;
            select configuracoes.fc_auditoria_remove_funcao('pessoal.jetomcomissaotiposessao');
            DROP SEQUENCE pessoal.jetomcomissaotiposessao_rh249_sequencial_seq;

SQL;
        $this->execute($sql);

    }

    public function down()
    {
        $this->removeDicionario();
        $this->removeTabela();
    }
}
