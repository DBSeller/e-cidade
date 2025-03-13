<?php

use Classes\PostgresMigration;

class M18716AjustaCampoArquivoObrasEnvio extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            ALTER TABLE projetos.obrasenvio ALTER COLUMN ob16_nomearq TYPE TEXT;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            ALTER TABLE projetos.obrasenvio ALTER COLUMN ob16_nomearq TYPE VARCHAR(50);
SQL
        );
    }
}
