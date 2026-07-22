<?php

use Classes\PostgresMigration;

class M15353AlteraTabelaAluno extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
insert into db_syscampo
    values(1011751,'ed47_visto','varchar(20)','Visto do Aluno','', 'Visto do Aluno',20,'t','t','f',0,'text','Visto do Aluno'),
          (1011752,'ed47_vistoresponsavel','varchar(20)','Visto do Responsável','', 'Visto do Responsável',20,'t','t','f',0,'text','Visto do Responsável'),
          (1011753,'ed47_rnm','varchar(20)','Registro Nacional de Migração','', 'Registro Nacional de Migração',20,'t','t','f',0,'text','Registro Nacional de Migração'),
          (1011754,'ed47_rnmresponsavel','varchar(20)','Registro Nacional de Migração do Responsável','', 'Registro Nacional de Migração',20,'t','t','f',0,'text','Registro Nacional de Migração');

insert into db_sysarqcamp
    values(1010051,1011751,75,0),
          (1010051,1011752,76,0),
          (1010051,1011753,77,0),
          (1010051,1011754,78,0);
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
delete from db_sysarqcamp where codarq = 1010051 and codcam in (1011751, 1011752, 1011753, 1011754);
delete from db_syscampo where codcam in (1011751, 1011752, 1011753, 1011754);
SQL
        );
    }

    private function upEstrutura()
    {
        $this->execute(<<<SQL
alter table aluno add column ed47_visto varchar (20);
alter table aluno add column ed47_vistoresponsavel varchar (20);
alter table aluno add column ed47_rnm varchar (20);
alter table aluno add column ed47_rnmresponsavel varchar (20);
SQL
        );
    }

    private function downEstrutura()
    {
        $this->execute(<<<SQL
alter table aluno drop column ed47_visto;
alter table aluno drop column ed47_vistoresponsavel;
alter table aluno drop column ed47_rnm;
alter table aluno drop column ed47_rnmresponsavel;
SQL
        );
    }
}
