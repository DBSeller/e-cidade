<?php

use Classes\PostgresMigration;

class M19618AlteraTamanhoCampoTipoDocumentoFiscal extends PostgresMigration
{

    function up()
    {

        $sql = <<<SQL

ALTER TABLE tipodocumentosfiscal ALTER COLUMN e12_descricao TYPE varchar(255);
update db_syscampo set conteudo = 'varchar(255)' where codcam = 14635;

SQL;
        $this->execute($sql);
    }


    function down()
    {

        $sql = <<<SQL

ALTER TABLE  ALTER COLUMN e12_descricao TYPE varchar(50);
update db_syscampo set conteudo = 'varchar(50)' where codcam = 14635;
SQL;
        $this->execute($sql);
    }
}
