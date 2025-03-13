<?php

use Classes\PostgresMigration;

class M11953EmpresasOptantesSimples extends PostgresMigration
{
    public function up()
    {
       $sSql = " insert into db_syscampodef values (10560, '5', 'Soc. Profissionais');";

       $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "delete from db_syscampodef where codcam = 10560 and defcampo = '5';";

        $this->execute($sSql);
    }
}
