<?php

use Classes\PostgresMigration;

class M14828AcertoEmpenhosPrestacaoContas extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP

insert into emppresta (e45_sequencial, e45_numemp, e45_data, e45_tipo, e45_codmov)
     select nextval('emppresta_e45_sequencial_seq'),
            e45_numemp,
            e45_data,
            e45_tipo,
            e81_codmov
       from empempenho
            join emppresta on e45_numemp = e60_numemp
            join empagemov on e81_numemp = e60_numemp
where e60_anousu = 2019
  and e81_cancelado is null
  and not exists (select 1 from emppresta where e45_numemp = e60_numemp and e45_codmov = e81_codmov);


SQL_UP
);
    }

    public function down()
    {
    }
}
