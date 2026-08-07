<?php

use Classes\PostgresMigration;

class M10960Mensageria extends PostgresMigration
{
    public function up()
    {
        $this->upMensageriaProcesso();
        $this->upDicionario();
    }

    public function down()
    {
        $this->downMensageriaProcesso();
        $this->downDicionario();
    }

    private function upMensageriaProcesso()
    {
        $this->execute(<<<SQL
          alter table protocolo.mensageriaprocesso add column p101_permitirnotificardepartamento boolean not null default false;
SQL
        );
    }

    private function downMensageriaProcesso()
    {
        $this->execute(<<<SQL
          alter table protocolo.mensageriaprocesso drop column p101_permitirnotificardepartamento;
SQL
        );
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
          insert into db_syscampo values(1009764,'p101_permitirnotificardepartamento','bool','Habilita as notificações do mensageria por departamento.','f', 'Permitir envio para departamento',1,'f','f','f',5,'text','Permitir envio para departamento');
          insert into db_sysarqcamp values(1010238,1009764,9,0);
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
          delete from db_sysarqcamp where codarq = 1010238 and codcam = 1009764 and seqarq = 9;
          delete from db_syscampo where codcam = 1009764;
SQL
        );
    }
}
