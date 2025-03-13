<?php

use Classes\PostgresMigration;

class M10760AlteracaoDescricaoCidade extends PostgresMigration
{
    public function up()
    {
        $this->execute("update cadendermunicipio set db72_descricao = 'EMBU DAS ARTES' where  db72_cadenderestado = 26 and db72_sequencial = 7098");
        $this->execute("delete from cadenderruacep where db86_sequencial = 748231");
    }

    public function down()
    {
        $this->execute("update cadendermunicipio set db72_descricao = 'Embu' where  db72_cadenderestado = 26 and db72_sequencial = 7098");
        $this->execute("insert into cadenderruacep values (748231,594434,'06817000')");
    }
}
