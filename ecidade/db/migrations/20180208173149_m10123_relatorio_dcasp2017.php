<?php

use Classes\PostgresMigration;

class M10123RelatorioDcasp2017 extends PostgresMigration
{
    public function up()
    {
        $this->execute("update orcparamseqorcparamseqcoluna set o116_ordem = 1 where o116_codparamrel = 171 and o116_orcparamseqcoluna = 177;");
        $this->execute("update orcparamseqorcparamseqcoluna set o116_ordem = 2 where o116_codparamrel = 171 and o116_orcparamseqcoluna = 178;");
    }

    public function down() {}
}