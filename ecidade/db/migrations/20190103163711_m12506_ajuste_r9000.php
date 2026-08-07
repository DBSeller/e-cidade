<?php

use Classes\PostgresMigration;

class M12506AjusteR9000 extends PostgresMigration
{

    public function up()
    {
        $this->execute("
          insert into db_syscampo values(1010270,'eso29_data','varchar(100)','Data','', 'data',100,'f','t','f',0,'text','data');   
          insert into db_sysarqcamp values(1010360,1010270,6,0);
        ");

        $this->execute("alter table avaliacaogruporespostaexclusaoeventosefd add column eso29_data timestamp default now();");
    }

    public function down()
    {
        $this->execute("
          delete from db_sysarqcamp where codarq = 1010360 and codcam = 1010270;
          delete from db_syscampo where codcam = 1010270;   
        ");

        $this->execute("alter table avaliacaogruporespostaexclusaoeventosefd drop column eso29_data;");


    }
}
