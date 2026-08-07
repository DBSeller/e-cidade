<?php

use Classes\PostgresMigration;

class M15756CorrecaoArquivoMenu extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_itensmenu set funcao = 'sau4_cgscorreto004.php?db_opcao=3' where id_item = 8073;");
    }

    public function down()
    {
    }
}
