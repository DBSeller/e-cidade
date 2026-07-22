<?php

use Classes\PostgresMigration;

class M15501AdicionaCampoProcedimento extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioUp();
        $this->execute("
            alter table escola.basemps add column ed34_procedimento int;
            alter table escola.basemps add constraint basemps_procedimento_fk foreign key (ed34_procedimento) references procedimento;
            create index basemps_procedimento_in on basemps(ed34_procedimento);
        ");
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->execute("
            alter table escola.basemps drop column ed34_procedimento;
        ");
    }

    private function dicionarioUp()
    {
        $this->execute("
            insert into db_syscampo values(1011149, 'ed34_procedimento', 'int4', 'Guardo o procedimento de avaliação da Disciplina', '0', 'Procedimento de Avaliação', 10, 't',  'f', 'f', 1, 'text', 'Procedimento de Avaliação');
            insert into db_sysarqcamp values(1010061,1011149,14,0);
            insert into db_sysforkey values(1010061,1011149,1,1010074,0);
            insert into db_sysindices values(1008560,'basemps_procedimento_in',1010061,'0');
            insert into db_syscadind values(1008560,1011149,1);
        ");

    }

    private function dicionarioDown()
    {
        $this->execute("
            delete from db_syscadind where codind = 1008560;
            delete from db_sysindices where codind = 1008560;
            delete from db_sysforkey where codcam = 1011149;
            delete from db_sysarqcamp where codcam = 1011149;
            delete from db_syscampo where codcam = 1011149;
        ");
    }
}
