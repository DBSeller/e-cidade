<?php

use Classes\PostgresMigration;

class M16123ParametroBicVeiculosIssqn extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDdl();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDdl();
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL

            insert into db_syscampo values(1011731,'q60_templatebicveiculo','varchar(150)','Template de documento agt para BIC de veículos','', 'Template BIC Veículos',150,'f','t','f',0,'text','Template BIC Veículos');
            delete from db_sysarqcamp where codarq = 664;
            insert into db_sysarqcamp values(664,4881,1,0);
            insert into db_sysarqcamp values(664,4883,2,0);
            insert into db_sysarqcamp values(664,5034,3,0);
            insert into db_sysarqcamp values(664,5047,4,0);
            insert into db_sysarqcamp values(664,5060,5,0);
            insert into db_sysarqcamp values(664,7436,6,0);
            insert into db_sysarqcamp values(664,7435,7,0);
            insert into db_sysarqcamp values(664,7434,8,0);
            insert into db_sysarqcamp values(664,7567,9,0);
            insert into db_sysarqcamp values(664,7433,10,0);
            insert into db_sysarqcamp values(664,8806,11,0);
            insert into db_sysarqcamp values(664,9013,12,0);
            insert into db_sysarqcamp values(664,9482,13,0);
            insert into db_sysarqcamp values(664,10634,14,0);
            insert into db_sysarqcamp values(664,10635,15,0);
            insert into db_sysarqcamp values(664,10636,16,0);
            insert into db_sysarqcamp values(664,10637,17,0);
            insert into db_sysarqcamp values(664,10639,18,0);
            insert into db_sysarqcamp values(664,10753,19,0);
            insert into db_sysarqcamp values(664,10968,20,0);
            insert into db_sysarqcamp values(664,12476,21,0);
            insert into db_sysarqcamp values(664,15671,22,0);
            insert into db_sysarqcamp values(664,16225,23,0);
            insert into db_sysarqcamp values(664,17090,24,0);
            insert into db_sysarqcamp values(664,18354,25,0);
            insert into db_sysarqcamp values(664,18353,26,0);
            insert into db_sysarqcamp values(664,19284,27,0);
            insert into db_sysarqcamp values(664,20592,28,0);
            insert into db_sysarqcamp values(664,20593,29,0);
            insert into db_sysarqcamp values(664,1010538,30,0);
            insert into db_sysarqcamp values(664,1011731,31,0);

SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL

            DELETE FROM db_sysarqcamp where codcam = 1011731;
            DELETE FROM db_syscampo where codcam = 1011731;

SQL
        );
    }

    public function upDdl()
    {
        $this->execute(<<<SQL

            ALTER TABLE issqn.parissqn
              ADD COLUMN q60_templatebicveiculo integer,
              ADD CONSTRAINT parissqn_db_documentotemplate_fk FOREIGN KEY (q60_templatebicveiculo)
                REFERENCES configuracoes.db_documentotemplate;

SQL
        );
    }

    public function downDdl()
    {
        $this->execute(<<<SQL

            ALTER TABLE issqn.parissqn
             DROP COLUMN q60_templatebicveiculo;

SQL
        );
    }
}
