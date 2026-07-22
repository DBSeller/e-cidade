<?php

use Classes\PostgresMigration;

class DocumentosProcesso extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
    }

    public function upDicionario()
    {
        $sql = <<<SQL_UP
insert into db_syscampo values(1010636,'p56_ouvidoriatipodado','int4','Tipo de dado do documento, válido para ouvidoria externa.','0', 'Tipo de Dado',10,'t','f','f',1,'text','Tipo de Dado');
insert into db_syscampodef values(1010636,'1','TEXTO LIVRE');
insert into db_syscampodef values(1010636,'2','NUMÉRICO');
insert into db_syscampodef values(1010636,'3','VALOR');
insert into db_syscampodef values(1010636,'4','ENDEREÇO');
insert into db_syscampodef values(1010636,'5','ARQUIVO');

insert into db_syscampo values(1010637,'p57_ouvidoriaobrigatorio','bool','Controla se o documento, vinculado ao tipo de processo, é obrigatório para sistemas externos.','false', 'Obrigatório( Sistema Externo )',1,'f','f','f',5,'text','Obrigatório( Sistema Externo )');
insert into db_sysarqcamp values(402,1010637,3,0);
SQL_UP;

        $this->execute($sql);
    }

    public function upDDL()
    {
        $sql = <<<SQL_UP
        alter table procdoc add column p56_ouvidoriatipodado int4;
        alter table procdoctipo add column p57_ouvidoriaobrigatorio boolean default false not null;
SQL_UP;

        $this->execute($sql);
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDDL();
    }

    public function downDicionario()
    {
        $sql = <<<SQL_DOWN
delete from db_sysarqcamp where codcam = 1010636;
delete from db_syscampo where codcam = 1010636;
delete from db_syscampodef where codcam = 1010636;

delete from db_sysarqcamp where codcam = 1010637;
delete from db_syscampo where codcam = 1010637;
SQL_DOWN;

        $this->execute($sql);
    }

    public function downDDL()
    {
        $sql = <<<SQL_DOWN
        alter table procdoc drop column p56_tipodado;
        alter table procdoctipo drop column p57_obrigatorio;
SQL_DOWN;

        $this->execute($sql);
    }
}
