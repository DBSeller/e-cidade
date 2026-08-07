<?php

use Classes\PostgresMigration;

class M15521AlteracaoDiarioAlunoResultadoFinal extends PostgresMigration
{

    public function up()
    {
        $this->execute("update db_syscampo set nulo = 't' where codcam = 1011138;");
        $this->execute("alter table escola.diarioalunoresultadofinal alter column ed165_resultado_final drop not null;");
    }

    public function down()
    {
        $this->execute("update db_syscampo set nulo = 'f' where codcam = 1011138;");
        $this->execute("alter table escola.diarioalunoresultadofinal alter column ed165_resultado_final set not null;");
    }
}
