<?php

use Classes\PostgresMigration;

class M16774AdicionaCampoAreaPublicidade extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
            INSERT INTO db_syscampo VALUES (1011869,'q30_areapublicidade','float4','Área de Publicidade','0', 'Área de Publicidade',15,'t','f','f',4,'text','Área de Publicidade');
            INSERT INTO db_sysarqcamp VALUES (47,1011869,7,0);
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
            DELETE FROM db_sysarqcamp WHERE codcam = 1011869;
            DELETE FROM db_syscampo WHERE codcam = 1011869;
SQL
        );
    }

    private function upEstrutura()
    {
        $this->execute(<<<SQL
            ALTER TABLE issqn.issquant ADD COLUMN q30_areapublicidade numeric(15, 2);
SQL
        );
    }

    private function downEstrutura()
    {
        $this->execute(<<<SQL
            ALTER TABLE issqn.issquant DROP COLUMN q30_areapublicidade;
SQL
        );
    }
}
