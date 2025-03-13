<?php

use Classes\PostgresMigration;

class M14230AcertoMovimentosEmpenhoPrestacaoContas extends PostgresMigration
{

    public function down()
    {

    }

    public function up()
    {
        $this->execute(<<<SQL_UP

 update emppresta
    set e45_codmov = e81_codmov
   from empagemov 
  where e81_numemp = emppresta.e45_numemp
    and e81_cancelado is null
    and e45_codmov is null

SQL_UP
);
    }
}
