<?php

use Classes\PostgresMigration;

class M18347 extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
update db_itensmenu set descricao = 'Demonstrativos' , help = 'Demonstrativos' where id_item = 228497;
update db_itensmenu set funcao = 'pla2_programas_estrategicos.php' where id_item = 228498;
update db_itensmenu set funcao = 'pla2_programas_gestao.php' where id_item = 228499;
update db_itensmenu set funcao = 'pla2_projecao_receita.php' where id_item = 228504;

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
update db_itensmenu set descricao = 'PPA', help = 'PPA' where id_item = 228497;
update db_itensmenu set funcao = 'pla2_programas_estrategicos.php?tipo=PPA' where id_item = 228498;
update db_itensmenu set funcao = 'pla2_programas_gestao.php?tipo=PPA' where id_item = 228499;
update db_itensmenu set funcao = 'pla2_projecao_receita.php?tipo=PPA' where id_item = 228504;
SQL
        );
    }
}
