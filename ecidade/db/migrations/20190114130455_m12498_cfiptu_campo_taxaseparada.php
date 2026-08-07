<?php

use Classes\PostgresMigration;

class M12498CfiptuCampoTaxaseparada extends PostgresMigration
{
    public function up()
    {
        $this->adicionaCampoCfiptu();
        $this->dicionarioDadosUp();
    }

    public function down()
    {
        $this->removeCampoCfiptu();
        $this->dicionarioDadosDown();
    }

    private function dicionarioDadosUp()
    {
        $sql = <<<SQL
            insert into db_syscampo values(1010302,'j18_taxaseparada','int4','Parâmetro responsável por gerenciar o com controle de geração de taxas separadas do débito anual de IPTU.','0', 'Calcular Taxas Separadas',1,'f','f','f',1,'text','Calcular Taxas Separadas');
            delete from db_sysarqcamp where codarq = 153;
            insert into db_sysarqcamp values(153,808,1,0);
            insert into db_sysarqcamp values(153,809,2,0);
            insert into db_sysarqcamp values(153,810,3,0);
            insert into db_sysarqcamp values(153,812,4,0);
            insert into db_sysarqcamp values(153,811,5,0);
            insert into db_sysarqcamp values(153,813,6,0);
            insert into db_sysarqcamp values(153,7320,7,0);
            insert into db_sysarqcamp values(153,7415,8,0);
            insert into db_sysarqcamp values(153,7623,9,0);
            insert into db_sysarqcamp values(153,7870,10,0);
            insert into db_sysarqcamp values(153,7932,11,0);
            insert into db_sysarqcamp values(153,7979,12,0);
            insert into db_sysarqcamp values(153,8646,13,0);
            insert into db_sysarqcamp values(153,8754,14,0);
            insert into db_sysarqcamp values(153,8756,15,0);
            insert into db_sysarqcamp values(153,8810,16,0);
            insert into db_sysarqcamp values(153,8980,17,0);
            insert into db_sysarqcamp values(153,9139,18,0);
            insert into db_sysarqcamp values(153,9542,19,0);
            insert into db_sysarqcamp values(153,9543,20,0);
            insert into db_sysarqcamp values(153,9544,21,0);
            insert into db_sysarqcamp values(153,9762,22,0);
            insert into db_sysarqcamp values(153,9856,23,0);
            insert into db_sysarqcamp values(153,9858,24,0);
            insert into db_sysarqcamp values(153,10824,25,0);
            insert into db_sysarqcamp values(153,10831,26,0);
            insert into db_sysarqcamp values(153,11059,27,0);
            insert into db_sysarqcamp values(153,18859,28,0);
            insert into db_sysarqcamp values(153,20545,29,0);
            insert into db_sysarqcamp values(153,21918,30,0);
            insert into db_sysarqcamp values(153,21919,31,0);
            insert into db_sysarqcamp values(153,1010302,32,0);
SQL;

        $this->execute($sql);
    }

    private function dicionarioDadosDown()
    {
        $sql = <<<SQL
        delete from db_sysarqcamp where codarq = 153 and codcam = 1010302;
        delete from db_syscampo where codcam = 1010302;
SQL;

        $this->execute($sql);
    } 

    private function adicionaCampoCfiptu()
    {
        $sql = "ALTER TABLE cadastro.cfiptu ADD COLUMN j18_taxaseparada INTEGER default 0";

        $this->execute($sql);
    }

    private function removeCampoCfiptu()
    {
        $sql = "ALTER TABLE cadastro.cfiptu DROP COLUMN j18_taxaseparada";

        $this->execute($sql);
    }

}
