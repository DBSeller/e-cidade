<?php

use Classes\PostgresMigration;

class M16554AdicionandoCampoAam extends PostgresMigration
{

    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDDL();
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL

            insert into db_syscampo values(1011817,'q172_aam','varchar(255)','Autorização automobilistica municipal','', 'Autorização Automobilistica Municipal',255,'f','t','f',0,'text','Autorização Automobilistica Municipal');
            delete from db_sysarqcamp where codarq = 1010602;
            insert into db_sysarqcamp values(1010602,1011688,1,1000956);
            insert into db_sysarqcamp values(1010602,1011689,2,0);
            insert into db_sysarqcamp values(1010602,1011691,3,0);
            insert into db_sysarqcamp values(1010602,1011692,4,0);
            insert into db_sysarqcamp values(1010602,1011693,5,0);
            insert into db_sysarqcamp values(1010602,1011694,6,0);
            insert into db_sysarqcamp values(1010602,1011695,7,0);
            insert into db_sysarqcamp values(1010602,1011696,8,0);
            insert into db_sysarqcamp values(1010602,1011697,9,0);
            insert into db_sysarqcamp values(1010602,1011698,10,0);
            insert into db_sysarqcamp values(1010602,1011699,11,0);
            insert into db_sysarqcamp values(1010602,1011700,12,0);
            insert into db_sysarqcamp values(1010602,1011701,13,0);
            insert into db_sysarqcamp values(1010602,1011702,14,0);
            insert into db_sysarqcamp values(1010602,1011703,15,0);
            insert into db_sysarqcamp values(1010602,1011704,16,0);
            insert into db_sysarqcamp values(1010602,1011817,17,0);

SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL

            delete from db_sysarqcamp where codcam = 1011817;
            delete from db_syscampo where codcam = 1011817;

SQL
        );

    }

    private function upDDL()
    {
        $this->execute(<<<SQL

            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl DISABLE;
            ALTER EVENT TRIGGER evtg_dicionario_gatilho_ddl_drop DISABLE;

            ALTER TABLE issqn.issveiculo ADD COLUMN q172_aam varchar(255);
            SELECT configuracoes.fc_auditoria_cria_funcao('issqn.issveiculo');

SQL
        );
    }

    private function downDDL()
    {
        $this->execute(<<<SQL

            ALTER TABLE issqn.issveiculo DROP COLUMN q172_aam;

SQL
        );
    }
}
