<?php

use Classes\PostgresMigration;

class M12101FinalidadesSaude extends PostgresMigration
{
    public function up()
    {
        $sql = <<<AIAIAI
select setval('finalidadepagamentofundeb_e151_sequencial_seq', max(e151_sequencial)) from finalidadepagamentofundeb; 
insert into finalidadepagamentofundeb values(nextval('finalidadepagamentofundeb_e151_sequencial_seq'), '53', 'Pagamento a prestadores públicos de saúde');
insert into finalidadepagamentofundeb values(nextval('finalidadepagamentofundeb_e151_sequencial_seq'), '54', 'Pagamento a pesquisas de saúde');
insert into finalidadepagamentofundeb values(nextval('finalidadepagamentofundeb_e151_sequencial_seq'), '55', 'Ressarcimento por escola municipalizada');
insert into finalidadepagamentofundeb values(nextval('finalidadepagamentofundeb_e151_sequencial_seq'), '56', 'Retificação de arrecadação');
insert into finalidadepagamentofundeb values(nextval('finalidadepagamentofundeb_e151_sequencial_seq'), '57', 'Transferência para transporte escolar municipal');
AIAIAI;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<UIUIUI
delete from finalidadepagamentofundeb where e151_codigo = '53';
delete from finalidadepagamentofundeb where e151_codigo = '54';
delete from finalidadepagamentofundeb where e151_codigo = '55';
delete from finalidadepagamentofundeb where e151_codigo = '56';
delete from finalidadepagamentofundeb where e151_codigo = '57';
UIUIUI;

        $this->execute($sql);
    }
}
