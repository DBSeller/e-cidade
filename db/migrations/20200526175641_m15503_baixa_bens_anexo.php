<?php

use Classes\PostgresMigration;

class M15503BaixaBensAnexo extends PostgresMigration
{
    public function up()
    {
        $this->upDDL();
        $this->upDicionario();
    }

    public function upDDL()
    {
        $this->execute(<<<SQL_UP
alter table bensmotbaixa add column t51_anexoobrigatorio boolean default false;
alter table bensbaix add column t55_documento oid default null;
SQL_UP
);
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL_UP
insert into db_syscampo values(1011318,'t51_anexoobrigatorio','bool','Controla se o tipo de baixa obriga anexar um documento.','false', 'Obrigatório Anexar Documento',1,'t','f','f',5,'text','Obrigatório Anexar Documento');
insert into db_syscampo values(1011319,'t55_documento','oid','Documento anexado a baixa de bem.','', 'Documento',1,'t','f','f',1,'text','Documento');
insert into db_sysarqcamp values(912,1011318,3,0);
insert into db_sysarqcamp values(917,1011319,5,0);
SQL_UP
        );
    }

    public function down()
    {
        $this->downDDL();
        $this->downDicionario();
    }

    public function downDDL()
    {
        $this->execute(<<<SQL_DOWN
alter table bensmotbaixa drop column t51_anexoobrigatorio;
alter table bensbaix drop column t55_documento;
SQL_DOWN
);
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL_DOWN
delete from db_sysarqcamp where codarq = 912 and codcam = 1011318;
delete from db_syscampo where codcam = 1011318;

delete from db_sysarqcamp where codarq = 917 and codcam = 1011319;
delete from db_syscampo where codcam = 1011319;
SQL_DOWN
        );
    }
}
