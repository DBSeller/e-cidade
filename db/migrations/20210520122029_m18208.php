<?php

use Classes\PostgresMigration;

class M18208 extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioUp();
    }

    public function down()
    {
        $this->dicionarioDown();
    }

    private function dicionarioUp()
    {
        // atualizado visualização dos menus
        $this->execute(<<<SQL
update db_itensmenu set libcliente = 'true' where id_item = 228361;
update db_itensmenu set libcliente = 'false' where id_item = 228500;

update db_itensmenu set funcao = 'pla2_programas_estrategicos.php?tipo=PPA' where id_item = 228498;
update db_itensmenu set funcao = 'pla2_programas_gestao.php?tipo=PPA' where id_item = 228499;
update db_itensmenu set funcao = 'pla2_projecao_receita.php?tipo=PPA' where id_item = 228504;

SQL
        );
    }

    private function dicionarioDown()
    {
        $this->execute(<<<SQL
update db_itensmenu set libcliente = 'false' where id_item = 228361;
update db_itensmenu set libcliente = 'true' where id_item = 228500;

update db_itensmenu set funcao = 'pla2_programas_estrategicos.php' where id_item = 228498;
update db_itensmenu set funcao = 'pla2_programas_gestao.php' where id_item = 228499;
update db_itensmenu set funcao = 'pla2_projecao_receita.php' where id_item = 228504;

SQL
        );
    }
}
