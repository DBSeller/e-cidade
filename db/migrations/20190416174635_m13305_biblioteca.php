<?php

use Classes\PostgresMigration;

class M13305Biblioteca extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            update exemplar set bi23_anoedicao = bi06_anoedicao, bi23_edicao = bi06_edicao
              from acervo
             where exemplar.bi23_acervo = acervo.bi06_seq;
        ");
    }
    public function down()
    {

    }
}
