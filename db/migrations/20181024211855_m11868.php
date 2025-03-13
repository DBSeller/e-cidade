<?php

use Classes\PostgresMigration;

class M11868 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
          insert into db_syscampo values(1010043,'k00_dtvencimento','text','Esse campo foi criado para ser utilizado no E-cidade online, para bloquear data de vencimento do recibo','', 'k00_dtvencimento',12,'t','t','f',0,'text','k00_dtvencimento');
          insert into db_sysarqcamp values(82,1010043,40,0);
          ALTER TABLE arretipo ADD COLUMN k00_dtvencimento varchar(11);
          
SQL;


    $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
          DELETE from db_sysarqcamp where codcam = 1010043;
          DELETE from db_syscampo where codcam = 1010043;           
          ALTER TABLE arretipo DROP COLUMN k00_dtvencimento RESTRICT;
SQL;

        $this->execute($sql);
    }
}
