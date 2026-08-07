<?php

use Classes\PostgresMigration;

class M15193TipoEnsino extends PostgresMigration
{
   public function up()
    {
        $this->dicionario();
        $this->execute("alter table escola.ensino add column ed10_tipo int;");
    }

    public function down()
    {
        $this->execute("
            delete from db_sysarqcamp where codcam = 1010908;
            delete from db_syscampo where codcam = 1010908;
        ");

        $this->execute("alter table escola.ensino drop column ed10_tipo;");
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_syscampo values(1010908,'ed10_tipo','int4','1 - Ensino Infantil 2 - Ensino Fundamental 3 - Ensino Médio 4 - Ensino Profissional','0', 'Tipo de Ensino',10,'t','f','f',1,'text','Tipo de Ensino');
            insert into db_sysarqcamp values(1010045,1010908,9,0);
        ");
    }
}
