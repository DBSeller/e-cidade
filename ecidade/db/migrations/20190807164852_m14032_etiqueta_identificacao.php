<?php

use Classes\PostgresMigration;

class M14032EtiquetaIdentificacao extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
    }

    public function upDicionario()
    {
        $sql = <<<SQL
            insert into db_syscampo values(1010672,'la49_modelocoletaamostra','int4','Modelo de Impressão Coleta de Amostra.','1', 'Modelo de Impressão Coleta de Amostra',10,'f','f','f',1,'text','Modelo de Impressão Coleta de Amostra');
            insert into db_sysarqcamp values(2909,1010672,4,0);
SQL;
        $this->execute($sql);
    }

    public function upDDL()
    {
        $sql = <<<SQL
            alter table lab_parametros add column la49_modelocoletaamostra int4 not null default 1;
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDDL();
    }

    public function downDicionario()
    {
        $sql = <<<SQL
            delete from db_sysarqcamp where codcam = 1010672;
            delete from db_syscampo where codcam = 1010672;
SQL;
        $this->execute($sql);
    }

    public function downDDL()
    {
        $sql = <<<SQL
            alter table lab_parametros drop column la49_modelocoletaamostra;
SQL;
        $this->execute($sql);
    }
}
