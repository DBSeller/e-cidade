<?php

use Classes\PostgresMigration;

class M12498AdicionaCampoQuant extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

alter table iptutaxacalv add column j152_quant float default 0;

insert into db_syscampo values(1010326,'j152_quant','float8','Alíquota referente a quantidade da taxa calculada.','0', 'Quantidade',10,'t','f','f',4,'text','Quantidade');
delete from db_sysarqcamp where codarq = 1010405;
insert into db_sysarqcamp values(1010405,1010290,1,1000813);
insert into db_sysarqcamp values(1010405,1010291,2,0);
insert into db_sysarqcamp values(1010405,1010292,3,0);
insert into db_sysarqcamp values(1010405,1010293,4,0);
insert into db_sysarqcamp values(1010405,1010294,5,0);
insert into db_sysarqcamp values(1010405,1010326,6,0);


SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL

delete from db_sysarqcamp where codcam = 1010326;
delete from db_syscampo where codcam = 1010326;

alter table iptutaxacalv drop column j152_quant;

SQL;

        $this->execute($sql);
    }
}
