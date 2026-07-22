<?php

use Classes\PostgresMigration;

class M12047AdvogadoPadrao extends PostgresMigration
{
    public function up()
    {
        $this->adicionaEstruturaAdvogadoPadrao();
    }

    public function down()
    {
        $this->removeEstruturaAdvogadoPadrao();
    }

    private function adicionaEstruturaAdvogadoPadrao()
    {
        $sql = "
            INSERT INTO db_syscampo 
            VALUES(1010586,
                   'v19_advogadopadrao',
                   'int4',
                   'Guarda o advogado que sera referenciado na rotina de inscrição em divida.',
                   '0',
                   'Advogado padrão',
                   10,
                   't',
                   'f',
                   'f',
                   1,
                   'text',
                   'Advogado padrão');
            INSERT INTO db_sysarqcamp VALUES(2029,1010586,15,0);

            ALTER TABLE parjuridico ADD COLUMN v19_advogadopadrao INTEGER;

            ALTER TABLE parjuridico
            ADD CONSTRAINT parjuridico_advogadopadrao_fk FOREIGN KEY (v19_advogadopadrao)
            REFERENCES advog;
        ";
        $this->execute($sql);
    }

    private function removeEstruturaAdvogadoPadrao()
    {
        $sql = "
            DELETE FROM db_sysarqcamp WHERE codcam = 1010586;
            DELETE FROM db_syscampo WHERE codcam = 1010586;
            ALTER TABLE parjuridico DROP COLUMN v19_advogadopadrao; 
        ";
        $this->execute($sql);
    }
}
