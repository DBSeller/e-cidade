<?php

use Classes\PostgresMigration;

class M12246AdicionaCampoLocalTrab extends PostgresMigration
{
    public function up()
    {
        $this->adicionaDicicionario();
        $this->adicionaColuna();
    }

    public function down()
    {
        $this->removeColuna();
        $this->removeDicionario();
    }

    public function adicionaDicicionario()
    {
        $sql = <<<SQL
            insert into db_syscampo values(1010100,'rh56_data','date','Data de inclusão do servidor no local de trabalho.','null', 'Data',10,'t','f','f',1,'text','Data');
            delete from db_sysarqcamp where codarq = 1543;
            insert into db_sysarqcamp values(1543,9041,1,504);
            insert into db_sysarqcamp values(1543,9017,2,0);
            insert into db_sysarqcamp values(1543,9018,3,0);
            insert into db_sysarqcamp values(1543,9042,4,0);
            insert into db_sysarqcamp values(1543,15046,5,0);
            insert into db_sysarqcamp values(1543,15047,6,0);
            insert into db_sysarqcamp values(1543,1010100,7,0);
SQL;
        $this->execute($sql);
    }

    public function removeDicionario()
    {

        $sql = <<<SQL
        delete from db_sysarqcamp where codarq = 1543 and codcam = 1010100;
        delete from db_syscampo where codcam = 1010100;
SQL;
        $this->execute($sql);
    }

    public function adicionaColuna()
    {
        $sql = "alter table pessoal.rhpeslocaltrab add column data date default null";
        $this->execute($sql);
    }

    public function removeColuna()
    {
        $sql = "alter table pessoal.rhpeslocaltrab drop column data";
        $this->execute($sql);
    }
}
