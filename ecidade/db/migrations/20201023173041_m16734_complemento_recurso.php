<?php

use Classes\PostgresMigration;

class M16734ComplementoRecurso extends PostgresMigration
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
insert into db_syscampo values(1011867,'o200_tribunal','bool','Usar no Tribunal de Contas','f', 'Tribunal de Contas',1,'f','f','f',5,'text','Tribunal de Contas');
update db_syscampo set nomecam = 'o200_descricao', descricao = 'Descrição do Complemento de Recurso', rotulo = 'Complemento', rotulorel = 'Complemento' where codcam = 1011275;
insert into db_sysarqcamp values(1010561,1011867,4,0);
SQL
        );
    }

    private function upEstrutura()
    {
        $this->execute(<<<SQL
        DROP INDEX IF EXISTS orctiporec_recurso_complemento_in;
        alter table orcamento.complementofonterecurso add column o200_tribunal bool default false;
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
        delete from db_sysarqcamp where codarq = 1010561 and codcam = 1011867;
        delete from db_syscampo  where codcam = 1011867;
SQL
        );
    }

    private function downEstrutura()
    {
        $this->execute(<<<SQL
        alter table orcamento.complementofonterecurso drop column o200_tribunal;
SQL
        );
    }
}
