<?php

use Classes\PostgresMigration;

class M15531JetomAdicionaCamposCompetenciaSessao extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicionario();
        $this->adicionaColunas();
        $this->acertoAtualizaCompetencia();
    }

    public function down()
    {
        $this->removeDicionario();
        $this->removeColunas();
    }

    public function adicionaDicionario()
    {
       $sql = <<<SQL
            insert into db_syscampo values(1011271,'rh247_mes','int4','contem o mes de uma competencia','0', 'mes da competencia',10,'f','f','f',1,'text','mes da competencia');
            insert into db_syscampo values(1011272,'rh247_ano','int4','o ano de uma competencia','0', 'ano da competencia',10,'f','f','f',1,'text','ano da competencia');
            delete from db_sysarqcamp where codarq = 1010497;
            insert into db_sysarqcamp values(1010497,1010885,1,1000866);
            insert into db_sysarqcamp values(1010497,1010886,2,0);
            insert into db_sysarqcamp values(1010497,1010887,3,0);
            insert into db_sysarqcamp values(1010497,1010888,4,0);
            insert into db_sysarqcamp values(1010497,1010889,5,0);
            insert into db_sysarqcamp values(1010497,1011271,6,0);
            insert into db_sysarqcamp values(1010497,1011272,7,0);
SQL;
       $this->execute($sql);
    }

    public function removeDicionario()
    {
        $sql = <<<SQL
                delete from db_sysarqcamp where codarq = 1010497;
                delete from db_syscampo where codcam in (1011271, 1011272);
SQL;
        $this->execute($sql);

    }

    public function adicionaColunas()
    {
        $sql = <<<SQL
                ALTER TABLE pessoal.jetomsessao ADD COLUMN rh247_mes int not null default 0;
                ALTER TABLE pessoal.jetomsessao ADD COLUMN rh247_ano int not null default 0;
SQL;
        $this->execute($sql);

    }

    public function removeColunas ()
    {
        $sql = <<<SQL
                alter table pessoal.jetomsessao drop column rh247_mes;
                alter table pessoal.jetomsessao drop column rh247_ano;
SQL;
        $this->execute($sql);
    }

    /**
     * @todo Alterar no update o mes e o ano da competencia baseado no contexto do cliente antes de rodar a migration
     */
    public function acertoAtualizaCompetencia()
    {
        $sql = <<<SQL
                UPDATE pessoal.jetomsessao SET rh247_mes = 7;
                UPDATE pessoal.jetomsessao SET rh247_ano = 2020;
SQL;
        $this->execute($sql);

    }
}
