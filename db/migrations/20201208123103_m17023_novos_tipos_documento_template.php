<?php

use Classes\PostgresMigration;

class M17023NovosTiposDocumentoTemplate extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL
        insert into db_documentotemplatetipo (db80_sequencial, db80_descricao)
            values (60, 'CRÉDITO EXTRAORDINARIO ARRECADAÇÃO A MAIOR'),
                   (61, 'CRÉDITO EXTRAORDINARIO POR SUPERAVIT FINANCEIRO'),
                   (62, 'CRÉDITO EXTRAORDINARIO POR OPERAÇÃO DE CREDITO');
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
        delete from db_documentotemplatetipo where db80_sequencial in (60,61,62);
SQL
        );
    }
}
