<?php

use Classes\PostgresMigration;

class M11615CargaRubricas extends PostgresMigration
{
    public function up()
    {
        $sql = "update avaliacao set db101_cargadados = 'select rh27_rubric as codigorubrica, rh27_instit as instituicao, rh27_descr as descricaorubrica from rhrubricas where rh27_ativo = true AND rh27_instit = fc_getsession(''DB_instit'')::int' where db101_sequencial = 3000016";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "update avaliacao set db101_cargadados = 'select rh27_rubric as codigorubrica, rh27_instit as instituicao, rh27_descr as descricaorubrica from rhrubricas where rh27_instit = fc_getsession(''DB_instit'')::int' where db101_sequencial = 3000016";
        $this->execute($sql);
    }
}
