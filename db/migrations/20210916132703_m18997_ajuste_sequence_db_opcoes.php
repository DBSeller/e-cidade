<?php

use Classes\PostgresMigration;

class M18997AjusteSequenceDbOpcoes extends PostgresMigration
{


    public function up()
    {

        $sql = <<<SQL
          select setval('db_opcoes_db153_sequencial_seq', (select max(db153_sequencial) from db_opcoes));
SQL;

       $this->execute($sql);

    }

    public function down()
    {

        return;

    }



}
