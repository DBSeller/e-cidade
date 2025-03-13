<?php

use Classes\PostgresMigration;

class M12304Biblioteca extends PostgresMigration
{
    public function up()
    {
        $this->dicionario();
        $this->estrutura();
    }

    private function dicionario()
    {
        $this->execute("
            update db_syscampo set nulo = 't' where codcam = 19583;
            update db_syscampo set nulo = 't'where codcam = 1008113;
            
            insert into db_syscampo values(1010408,'bi23_anoedicao','varchar(8)','Ano da Edição ou copyright','', 'Ano da Edição',8,'t','f','f',0,'text','Ano da Edição');
            insert into db_syscampo values(1010409,'bi23_edicao','varchar(20)','Edição','', 'Edição',20,'t','t','f',0,'text','Edição');
            insert into db_sysarqcamp values(1010151,1010409,8,0);
            insert into db_sysarqcamp values(1010151,1010408,9,0);
        ");
    }

    private function estrutura()
    {
        $this->execute("
            alter table acervo alter column bi06_anoedicao drop not null;
            alter table acervo alter column bi06_edicao drop not null;
            alter table exemplar add column bi23_anoedicao varchar(8) default null;
            alter table exemplar add column bi23_edicao varchar(20) default null;
        ");
    }

    public function down()
    {
        $this->execute("
            update db_syscampo set nulo = 'f' where codcam = 19583;
            update db_syscampo set nulo = 'f' where codcam = 1008113;
            
            delete from db_sysarqcamp where codcam in (1010408, 1010409);
            delete from db_syscampo where codcam in (1010408, 1010409);
            
            alter table acervo alter column bi06_anoedicao set not null;
            alter table acervo alter column bi06_edicao set not null;
            alter table exemplar drop column bi23_anoedicao;
            alter table exemplar drop column bi23_edicao;
        ");
    }
}
