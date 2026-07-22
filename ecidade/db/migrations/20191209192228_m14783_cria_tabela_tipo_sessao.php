<?php

use Classes\PostgresMigration;

class M14783CriaTabelaTipoSessao extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicionario();
        $this->adicionaTabela();
        $this->adicionaRegistros();
    }

    public function adicionaDicionario()
    {
        $sql = "
            insert into db_sysarquivo values (1010485, 'jetomtiposessao', 'Tipo de sessão das sessoes do jetom', 'rh240', '2019-12-09', 'Tipo Sessao', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (28,1010485);
            insert into db_syscampo values(1010822,'rh240_sequencial','int4','Código sequencial do tipo de sessão','0', 'Código do tipo de Sessão',10,'f','f','f',1,'text','Código do tipo de Sessão');
            insert into db_syscampo values(1010823,'rh240_descricao','varchar(100)','Descrição do tipo de sessão','', 'Descrição',100,'f','t','f',0,'text','Descrição');
            insert into db_syscampo values(1010824,'rh240_ativo','bool','Determina se o tipo se sessão esta ativo.','f', 'Ativo',1,'f','f','f',5,'text','Ativo');
            delete from db_sysarqcamp where codarq = 1010485;
            insert into db_sysarqcamp values(1010485,1010822,1,0);
            insert into db_sysarqcamp values(1010485,1010823,2,0);
            insert into db_sysarqcamp values(1010485,1010824,3,0);
            delete from db_sysprikey where codarq = 1010485;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010485,1010822,1,1010822);
";
        $this->execute($sql);
    }

    public function adicionaTabela()
    {
        $sql = "CREATE TABLE pessoal.jetomtiposessao(
                rh240_sequencial		int4 NOT NULL default 0,
                rh240_descricao		varchar(100) NOT NULL ,
                rh240_ativo		bool default 't',
                CONSTRAINT jetomtiposessao_sequ_pk PRIMARY KEY (rh240_sequencial));

                select configuracoes.fc_auditoria_cria_funcao('pessoal.jetomtiposessao');
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $this->removeDicionario();
        $this->removeTabela();
    }

    public function removeDicionario()
    {
        $sql = "
            delete from db_sysprikey where codarq = 1010485;
            delete from db_sysarqcamp where codarq = 1010485;
            delete from db_syscampo where codcam in (1010822, 1010823, 1010824);
            delete from db_sysarqmod where codarq in (1010485);
            delete from db_sysarquivo where codarq in (1010485);
        ";
        $this->execute($sql);
    }

    public function removeTabela()
    {
        $sql = "drop table pessoal.jetomtiposessao;
                select configuracoes.fc_auditoria_remove_funcao('pessoal.jetomtiposessao');";
        $this->execute($sql);
    }

    public function adicionaRegistros()
    {
        $sql = "
            insert into pessoal.jetomtiposessao values (1, 'Normal');
            insert into pessoal.jetomtiposessao values (2, 'Extraordinaria');
            insert into pessoal.jetomtiposessao values (3, 'Urgente');
        ";

        $this->execute($sql);
    }
}
