<?php

use Classes\PostgresMigration;

class M13876CampoProrrogacaoMaternidade extends PostgresMigration
{
    public function up(){
        $sql = "insert into db_syscampo values(1010763,'r33_rubprorrogacaomaternidade','varchar(4)','Este campo representa a rubrica de prorrogação de maternidade','', 'Rubrica prorrogação maternidade',4,'t','t','f',0,'text','Rubrica prorrogação maternidade');";
        $sql .= "insert into db_sysarqcamp values(561,1010763,21,0);";
        $sql .= "alter table inssirf add column r33_rubprorrogacaomaternidade varchar(4);";
        $this->execute($sql);
    }
    public function down(){
        $sql = "delete from db_sysarqcamp where codcam = 1010763;";
        $sql.= "delete from db_syscampo where codcam = 1010763;";
        $sql.= "alter table inssirf drop column r33_rubprorrogacaomaternidade;";
        $this->execute($sql);
    }
}
