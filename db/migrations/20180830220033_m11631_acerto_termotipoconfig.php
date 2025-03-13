<?php

use Classes\PostgresMigration;

class M11631AcertoTermotipoconfig extends PostgresMigration
{
    public function up()
    {
        $this->execute("
do $$ begin
    create table if not exists w11631termotipoconfig as select * from termotipoconfig;
end $$;

update termotipoconfig
   set k42_tiponovo = k42_tipoorigem, k42_tipoorigem = k42_tiponovo
 where k42_cadtipo = 7
   and k42_tiponovo = (select k00_tipo 
                         from arretipo 
                        where k00_descr = 'COBRANÇA ADM - FAZENDA')
   and k42_tipoorigem = (select k00_tipo 
                           from arretipo 
                          where k00_descr = 'PACELAMENTO COB ADM - FAZENDA');
        ");
    }

    public function down()
    {
        return;
    }
}
