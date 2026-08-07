<?php

use Classes\PostgresMigration;

class M18934ProtocoloLinkDirecionamentoWiki extends PostgresMigration
{
    public function up()
    {
        $this->execute("update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/protocolo/#!consultas_processo.md' where id_item = 9227");
    }

    public function down()
    {
        $this->execute("update db_itensmenu set help = 'Consulta de Processo' where id_item = 9227");
    }
}
