<?php

use Classes\PostgresMigration;

class M15528InclusaoDosCamposDeDataNaComissaoJetom extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicionario();
        $this->adicionaNaTabela();
    }

    public function down()
    {
        $this->removeDicionario();
        $this->removeDaTabela();
    }

    public function adicionaDicionario()
    {
       $sql = <<<SQL
            insert into db_syscampo values(1011193,'rh242_datainicio','date','data inicio de uma comissão','null', 'data inicio',10,'f','f','f',1,'text','data inicio');
            insert into db_syscampo values(1011194,'rh242_datafim','date','data fim de uma comissão','null', 'data fim',10,'f','f','f',1,'text','data fim');
            delete from db_sysarqcamp where codarq = 1010487;
            insert into db_sysarqcamp values(1010487,1010828,1,1000859);
            insert into db_sysarqcamp values(1010487,1010829,2,0);
            insert into db_sysarqcamp values(1010487,1010830,3,0);
            insert into db_sysarqcamp values(1010487,1011193,4,0);
            insert into db_sysarqcamp values(1010487,1011194,5,0);
SQL;
       $this->execute($sql);
    }

    public function removeDicionario()
    {
        $sql = <<<SQL
                delete from db_sysarqcamp where codarq = 1010487;
                delete from db_syscampo where codcam in (1011193, 1011194);
SQL;
        $this->execute($sql);

    }

    public function adicionaNaTabela()
    {
        $sql = <<<SQL
                ALTER TABLE pessoal.jetomcomissao ADD COLUMN rh242_datainicio date;
                ALTER TABLE pessoal.jetomcomissao ADD COLUMN rh242_datafim date;
SQL;
        $this->execute($sql);

    }

    public function removeDaTabela ()
    {
        $sql = <<<SQL
                alter table pessoal.jetomcomissao drop column rh242_datainicio;
                alter table pessoal.jetomcomissao drop column rh242_datafim;
SQL;
        $this->execute($sql);
    }
}
